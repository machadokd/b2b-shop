<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'name' => 'Administrador',
            'email' => 'admin@loja.com',
            'password' => Hash::make('password'),
        ]);

        // Cliente fixo
        $clienteUser = User::factory()->customer()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@loja.com',
            'password' => Hash::make('password'),
        ]);
        $clienteDemo = Customer::factory()->withUser($clienteUser)->create([
            'company_name' => 'Empresa Demo Lda',
            'nif' => '500000000',
        ]);
        $enderecoDemo = Address::factory()->create([
            'customer_id' => $clienteDemo->id,
            'is_default' => true,
        ]);

        // 4 clientes adicionais
        $clientes = Customer::factory(4)->create()->each(function ($customer) {
            Address::factory()->create([
                'customer_id' => $customer->id,
                'is_default' => true,
            ]);
            Address::factory()->create(['customer_id' => $customer->id]);
        });

        // 2 catálogos
        $catalogo1 = Catalog::factory()->create(['name' => 'Catálogo Geral', 'slug' => 'catalogo-geral']);
        $catalogo2 = Catalog::factory()->create(['name' => 'Catálogo Premium', 'slug' => 'catalogo-premium']);

        // 4 categorias (2 com subcategorias)
        $cat1 = Category::factory()->create(['name' => 'Electrónica', 'slug' => 'electronica']);
        $cat2 = Category::factory()->create(['name' => 'Escritório', 'slug' => 'escritorio']);
        Category::factory()->withParent($cat1)->create(['name' => 'Computadores', 'slug' => 'computadores']);
        Category::factory()->withParent($cat2)->create(['name' => 'Mobiliário', 'slug' => 'mobiliario']);

        // 20 produtos activos
        $produtosActivos = Product::factory(20)->active()->create(['category_id' => $cat1->id]);

        // 5 produtos inactivos
        Product::factory(5)->inactive()->create(['category_id' => $cat2->id]);

        // Associar produtos aos catálogos
        $catalogo1->products()->attach($produtosActivos->take(15)->pluck('id'));
        $catalogo2->products()->attach($produtosActivos->take(8)->pluck('id'));

        // Encomendas em vários estados para o cliente demo
        foreach ([OrderStatus::Pending, OrderStatus::Confirmed, OrderStatus::Shipped, OrderStatus::Completed, OrderStatus::Cancelled] as $status) {
            $order = Order::factory()->create([
                'customer_id' => $clienteDemo->id,
                'address_id' => $enderecoDemo->id,
                'status' => $status,
            ]);
            $items = $produtosActivos->random(fake()->numberBetween(1, 3));
            foreach ($items as $produto) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $produto->id,
                    'unit_price' => $produto->price,
                    'quantity' => fake()->numberBetween(1, 5),
                ]);
            }
        }

        // Algumas encomendas para outros clientes
        $clientes->each(function ($customer) use ($produtosActivos) {
            $address = $customer->addresses()->first();
            $order = Order::factory()->create([
                'customer_id' => $customer->id,
                'address_id' => $address->id,
            ]);
            $produto = $produtosActivos->random();
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $produto->id,
                'unit_price' => $produto->price,
                'quantity' => fake()->numberBetween(1, 3),
            ]);
        });
    }
}
