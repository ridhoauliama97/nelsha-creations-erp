<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Account\Models\Tax;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;

/**
 * Seeds the Nelsha Creations instance configuration on top of the standard
 * plugin seed data.
 *
 * Run AFTER `erp:install` (and its plugin installs):
 *
 *   php artisan db:seed --class=InstanceSeeder
 *
 * The seeding is idempotent: values are only written when they differ from
 * the stored state, so it is safe to run repeatedly. Business placeholder
 * data (company email/phone/address) is intentionally left to the UI.
 */
class InstanceSeeder extends Seeder
{
    /**
     * Resolved configuration values for this instance.
     */
    protected string $companyName = 'Nelsha Creations';

    protected string $countryCode = 'ID';

    protected string $currencyIsoCode = 'IDR';

    protected int $currencyIsoNumeric = 360;

    protected string $userLanguage = 'id';

    protected float $taxAmount = 11.0;

    protected string $taxFormula = 'price_unit * 0.11';

    protected string $taxLabel = 'PPN 11 %';

    public function run(): void
    {
        $this->configureCompany()
            ->configureTaxes()
            ->configureSettings()
            ->configureUserLanguages();
    }

    protected function info(string $message): void
    {
        $this->command?->info($message);
    }

    protected function warn(string $message): void
    {
        $this->command?->warn($message);
    }

    /**
     * Rename the default company and its partner record to the real business
     * name, and point it at the Indonesian country and IDR currency.
     */
    protected function configureCompany(): static
    {
        $company = Company::query()->first();

        if (! $company) {
            $this->warn('No default company found — skipping company configuration.');

            return $this;
        }

        $countryId = Country::query()->whereRaw('LOWER(code) = ?', [strtolower($this->countryCode)])->value('id');

        $currencyId = DB::table('currencies')
            ->where('name', $this->currencyIsoCode)
            ->value('id')
            ?? DB::table('currencies')->where('iso_numeric', $this->currencyIsoNumeric)->value('id');

        $this->patchRecord($company, array_filter([
            'name'       => $company->name !== $this->companyName ? $this->companyName : null,
            'country_id' => $countryId && (int) $company->country_id !== (int) $countryId ? $countryId : null,
            'currency_id'=> $currencyId && (int) $company->currency_id !== (int) $currencyId ? $currencyId : null,
        ]), "company '{$company->name}'");

        if ($company->partner_id) {
            $partner = Partner::query()->whereKey($company->partner_id)->first();

            if ($partner && $partner->name !== $this->companyName) {
                $this->patchRecord($partner, ['name' => $this->companyName], "partner '{$partner->name}'");
            }
        }

        return $this;
    }

    /**
     * Convert the seeded placeholder taxes (sale + purchase) into the PPN 11 %
     * configuration used by this instance.
     */
    protected function configureTaxes(): static
    {
        $company = Company::query()->first();

        if (! $company) {
            $this->warn('No default company found — skipping tax configuration.');

            return $this;
        }

        $countryId = Country::query()->whereRaw('LOWER(code) = ?', [strtolower($this->countryCode)])->value('id');

        $taxes = Tax::query()
            ->where('company_id', $company->getKey())
            ->whereIn('type_tax_use', ['sale', 'purchase'])
            ->get();

        if ($taxes->isEmpty()) {
            $this->warn('No sale/purchase taxes found — run the accounts plugin install first.');

            return $this;
        }

        foreach ($taxes as $tax) {
            $updates = array_filter([
                'name'          => $tax->name !== $this->taxLabel ? $this->taxLabel : null,
                'amount'        => (float) $tax->amount !== $this->taxAmount ? $this->taxAmount : null,
                'formula'       => $tax->formula !== $this->taxFormula ? $this->taxFormula : null,
                'invoice_label' => $tax->invoice_label !== $this->taxLabel ? $this->taxLabel : null,
                'country_id'    => $countryId && (int) $tax->country_id !== (int) $countryId ? $countryId : null,
            ], fn ($value): bool => $value !== null);

            if (filled($tax->description)) {
                $updates['description'] = null;
            }

            if ($updates === []) {
                $this->info("Tax '{$tax->name}' ({$tax->type_tax_use->value}) already configured.");

                continue;
            }

            $tax->forceFill($updates)->save();

            $this->info("Tax '{$tax->name}' ({$tax->type_tax_use->value}) updated: ".implode(', ', array_keys($updates)).'.');
        }

        return $this;
    }

    /**
     * Align the spatie/laravel-settings payloads (default currency, fiscal
     * country, sale/purchase tax defaults) with the instance values.
     */
    protected function configureSettings(): static
    {
        $company = Company::query()->first();

        $this->upsertSetting('currency', 'default_currency_id', null, $this->currencyId());

        if ($company) {
            $this->upsertSetting('accounts_taxes', 'account_fiscal_country_id', $company->getKey(), $this->countryId());
        }

        foreach (['sale' => 'account_sale_tax_id', 'purchase' => 'account_purchase_tax_id'] as $use => $setting) {
            $taxId = Tax::query()
                ->where('company_id', $company?->getKey() ?? 0)
                ->where('type_tax_use', $use)
                ->value('id');

            if ($taxId && $company) {
                $this->upsertSetting('accounts_taxes', $setting, $company->getKey(), (int) $taxId);
            }
        }

        return $this;
    }

    /**
     * Keep every backend user on the instance language (Indonesian).
     */
    protected function configureUserLanguages(): static
    {
        $changed = User::query()
            ->where('language', '!=', $this->userLanguage)
            ->update(['language' => $this->userLanguage, 'updated_at' => now()]);

        $this->info($changed > 0
            ? "User language set to '{$this->userLanguage}' for {$changed} user(s)."
            : "All users already use language '{$this->userLanguage}'.");

        return $this;
    }

    /**
     * Insert or update a settings row only when its payload differs.
     */
    protected function upsertSetting(string $group, string $name, ?int $companyId, int|string $value): void
    {
        $payload = json_encode($value);

        $query = DB::table('settings')->where('group', $group)->where('name', $name);

        $setting = $query->where('company_id', $companyId)->first();

        if ($setting && $setting->payload === $payload) {
            $this->info("Setting '{$group}.{$name}' already up to date.");

            return;
        }

        if ($setting) {
            DB::table('settings')->where('id', $setting->id)->update([
                'payload'    => $payload,
                'updated_at' => now(),
            ]);

            $this->info("Setting '{$group}.{$name}' updated.");

            return;
        }

        DB::table('settings')->insert([
            'group'      => $group,
            'name'       => $name,
            'company_id' => $companyId,
            'locked'     => 0,
            'payload'    => $payload,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("Setting '{$group}.{$name}' created.");
    }

    /**
     * Apply the given attribute changes to a model when present.
     *
     * @param  array<string, mixed>  $updates
     */
    protected function patchRecord($model, array $updates, string $label): void
    {
        $updates = array_filter($updates, fn ($value): bool => $value !== null);

        if ($updates === []) {
            $this->info("{$label} already configured.");

            return;
        }

        $model->update($updates);

        $this->info("{$label} updated: ".implode(', ', array_keys($updates)).'.');
    }

    /**
     * The Indonesian country id, if available.
     */
    protected function countryId(): ?int
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        return $cached = Country::query()
            ->whereRaw('LOWER(code) = ?', [strtolower($this->countryCode)])
            ->value('id');
    }

    /**
     * The IDR currency id, if available.
     */
    protected function currencyId(): ?int
    {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        return $cached = (int) (DB::table('currencies')
            ->where('name', $this->currencyIsoCode)
            ->value('id')
            ?? DB::table('currencies')->where('iso_numeric', $this->currencyIsoNumeric)->value('id'));
    }
}
