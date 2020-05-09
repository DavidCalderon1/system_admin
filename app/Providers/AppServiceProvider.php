<?php

namespace App\Providers;

use App\Repositories\EloquentCityRepository;
use App\Repositories\EloquentCountryRepository;
use App\Repositories\EloquentInventoryCategoryRepository;
use App\Repositories\EloquentPaymentMethodRepository;
use App\Repositories\EloquentPermissionRepository;
use App\Repositories\EloquentProductRepository;
use App\Repositories\EloquentProductWarehouseRepository;
use App\Repositories\EloquentRoleRepository;
use App\Repositories\EloquentStateRepository;
use App\Repositories\EloquentThirdPartiesRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\EloquentWarehousesRepository;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;
use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\StateRepositoryInterface;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;
use App\Repositories\Purchases\EloquentPurchasePaymentRepository;
use App\Repositories\Purchases\EloquentPurchaseProductRepository;
use App\Repositories\Purchases\EloquentPurchaseRepository;
use App\Repositories\Purchases\Interfaces\MainPurchaseRepositoryInterface;
use App\Repositories\Purchases\Interfaces\PurchasePaymentRepositoryInterface;
use App\Repositories\Purchases\Interfaces\PurchaseProductRepositoryInterface;
use App\Repositories\Purchases\Interfaces\PurchaseRepositoryInterface;
use App\Repositories\Purchases\EloquentMainPurchaseRepository;
use App\Repositories\Sales\EloquentMainSaleRepository;
use App\Repositories\Sales\EloquentSalePaymentRepository;
use App\Repositories\Sales\EloquentSaleProductRepository;
use App\Repositories\Sales\EloquentSaleRepository;
use App\Repositories\Sales\Interfaces\MainSaleRepositoryInterface;
use App\Repositories\Sales\Interfaces\SalePaymentRepositoryInterface;
use App\Repositories\Sales\Interfaces\SaleProductRepositoryInterface;
use App\Repositories\Sales\Interfaces\SaleRepositoryInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use App\UsesCases\SalesUseCase;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * @var string[]
     */
    protected $classes = [
        //Repositories
        UserRepositoryInterface::class => EloquentUserRepository::class,
        RoleRepositoryInterface::class => EloquentRoleRepository::class,
        PermissionRepositoryInterface::class => EloquentPermissionRepository::class,
        CountryRepositoryInterface::class => EloquentCountryRepository::class,
        StateRepositoryInterface::class => EloquentStateRepository::class,
        CityRepositoryInterface::class => EloquentCityRepository::class,
        ThirdPartiesRepositoryInterface::class => EloquentThirdPartiesRepository::class,
        InventoryCategoryRepositoryInterface::class => EloquentInventoryCategoryRepository::class,
        WarehousesRepositoryInterface::class => EloquentWarehousesRepository::class,
        ProductRepositoryInterface::class => EloquentProductRepository::class,
        ProductWarehouseRepositoryInterface::class => EloquentProductWarehouseRepository::class,
        PaymentMethodRepositoryInterface::class => EloquentPaymentMethodRepository::class,
        //sale repositories
        SaleRepositoryInterface::class => EloquentSaleRepository::class,
        SaleProductRepositoryInterface::class => EloquentSaleProductRepository::class,
        SalePaymentRepositoryInterface::class => EloquentSalePaymentRepository::class,
        MainSaleRepositoryInterface::class => EloquentMainSaleRepository::class,
        //purchase repositories
        PurchaseRepositoryInterface::class => EloquentPurchaseRepository::class,
        PurchaseProductRepositoryInterface::class => EloquentPurchaseProductRepository::class,
        PurchasePaymentRepositoryInterface::class => EloquentPurchasePaymentRepository::class,
        MainPurchaseRepositoryInterface::class => EloquentMainPurchaseRepository::class,


        //UsesCases

        SalesUseCaseInterface::class => SalesUseCase::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->classes as $abstract => $class) {
            $this->app->bind($abstract, function () use ($class) {
                return app($class);
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
