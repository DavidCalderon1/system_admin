<?php

namespace Tests\Feature\app\Http\Controller\User;

use App\Constants\PermissionsConstants;
use App\Models\Permission;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class UserListControllerTest
 * @package Tests\Feature\app\Http\Controller\User
 */
class UserListControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $permission = factory(Permission::class)->create([
            'name' => 'User list',
            'slug' => PermissionsConstants::USER_LIST,
        ]);

        $this->user = factory(User::class)->create();
        $this->user->permissions()->attach([$permission->id]);
    }

    /**
     * Prueba que si el usuario no tiene permisos no redireccione a la vista 404
     */
    public function testIndex404()
    {
        $this->user->permissions()->sync([]);
        $response = $this->actingAs($this->user)->get(route('users.index'));
        $response->assertStatus(404);
    }

    /**
     * Prueba que si el usuario tiene permisos para listar ususarios le muestre la vista del gird
     */
    public function testIndex200()
    {
        $response = $this->actingAs($this->user)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('Users');
    }

    /**
     * Prueba la autorizacion de la funcion list
     */
    public function testList401()
    {
        $this->user->permissions()->sync([]);
        $response = $this->actingAs($this->user)->get(route('user.list'), [
            'Accept' => 'application/json',
        ]);
        $response->assertStatus(401);
    }

    /**
     * Prueba que si se envia un campo mal en el request retorne el error y el codigo 422
     */
    public function testList422()
    {
        $response = $this->actingAs($this->user)->get(route('user.list', [
            'name' => $this->user->name,
            'email' => 'Esto no es un email'
        ]), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => [
                    __('validation.email', ['attribute' => 'email'])
                ]
            ]
        ]);
    }

    /**
     * Prueba que si noe encuentra usuarios retorne el codigo 404 y su respectivo mensaje
     */
    public function testList404()
    {
        $filer = [
            'name' => $this->user->name,
        ];
        $userRepositoryMock = \Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getPagination')->with(5, $filer)
            ->andReturn([])
            ->getMock();

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);

        $response = $this->actingAs($this->user)->get(route('user.list', $filer), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(404);

        $response->assertJson([
            'errors' => [
                'title' => 'Not found',
                'message' => __('users.users_not_found'),
                'code' => 404
            ]
        ]);
    }

    /**
     * Prueba que si encuentra usuarios retorne el codigo 200 y su respectivo body
     */
    public function testList200()
    {
        $filer = [
            'name' => $this->user->name,
        ];
        $userRepositoryMock = \Mockery::mock(UserRepositoryInterface::class);
        $userRepositoryMock->shouldReceive('getPagination')->with(5, $filer)
            ->andReturn($this->user->paginate()->toArray())
            ->getMock();

        $this->app->instance(UserRepositoryInterface::class, $userRepositoryMock);

        $response = $this->actingAs($this->user)->get(route('user.list', $filer), [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'users',
                'pagination' => [
                    'current_page',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'next_page_url',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ]
            ]
        ]);
    }
}
