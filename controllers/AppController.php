<?php
namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

/**
 * AppController extends Controller and implements the behaviors() method
 * where you can specify the access control ( AC filter + RBAC ) for your controllers and their actions.
 */
class AppController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     * Here we use RBAC in combination with AccessControl filter.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    /*************************
                    * Site
                    *************************/
                            [
                                'controllers' => ['site'],
                                'actions' => ['index', 'acerca-de', 'permisos', 'error'],
                                'allow' => true,
                            ],

                    /*************************
                    * Admin
                    *************************/
                        // Dashboard
                            [
                                'controllers' => ['admin/dashboard'],
                                'actions' => ['index'],
                                'allow' => true,
                                'roles' => ['dashboardAdmin'],
                            ],
                        // Dashboard
                            [
                                'controllers' => ['admin/user'],
                                'actions' => ['check-promocion-especial','promocion-autoriza','cancel-promocion-especial'],
                                'allow' => true,
                                'roles' => ['configuracionSitio'],
                            ],
                        // Usuarios
                            [
                                'controllers' => ['admin/user'],
                                'actions' => ['login', 'signup', 'activate-account', 'request-password-reset', 'reset-password'],
                                'allow' => true,
                                'roles' => ['?'],
                            ],
                            [
                                'controllers' => ['admin/user'],
                                'actions' => ['logout', 'change-password', "mi-perfil"],
                                'allow' => true,
                                'roles' => ['@'],
                            ],
                            [
                                'controllers' => ['admin/user'],
                                'actions' => ['index', 'users-json-btt', 'view', 'historial-cambios','user-ajax','enable-acceso-app','desabled-acceso-app','user-agentes-supervisor-ajax'],
                                'allow' => true,
                                'roles' => ['userView'],
                            ],
                            [
                                'controllers' => ['admin/user'],
                                'actions' => ['create'],
                                'allow' => true,
                                'roles' => ['userCreate'],
                            ],
                            [
                                'controllers' => ['admin/user'],
                                'actions' => ['update'],
                                'allow' => true,
                                'roles' => ['userUpdate'],
                            ],
                            [
                                'controllers' => ['admin/user'],
                                'actions' => ['delete'],
                                'allow' => true,
                                'roles' => ['userDelete'],
                            ],
                        // Perfiles
                            [
                                'controllers' => ['admin/perfil'],
                                'actions' => ['index','perfiles-json-btt', 'view'],
                                'allow' => true,
                                'roles' => ['perfilUserView'],
                            ],
                            [
                                'controllers' => ['admin/perfil'],
                                'actions' => ['create'],
                                'allow' => true,
                                'roles' => ['perfilUserCreate'],
                            ],
                            [
                                'controllers' => ['admin/perfil'],
                                'actions' => ['update'],
                                'allow' => true,
                                'roles' => ['perfilUserUpdate'],
                            ],
                            [
                                'controllers' => ['admin/perfil'],
                                'actions' => ['delete'],
                                'allow' => true,
                                'roles' => ['perfilUserDelete'],
                            ],
                        // Listas desplegables
                            [
                                'controllers' => ['admin/listas-desplegables'],
                                'actions'     => ['index', 'listas', 'items', 'tabla'],
                                'allow'       => true,
                                'roles'       => ['listaDesplegableView'],
                            ],
                            [
                                'controllers' => ['admin/listas-desplegables'],
                                'actions'     => ['create-ajax'],
                                'allow'       => true,
                                'roles'       => ['listaDesplegableCreate'],
                            ],
                            [
                                'controllers' => ['admin/listas-desplegables'],
                                'actions'     => ['update-ajax', 'sort-ajax'],
                                'allow'       => true,
                                'roles'       => ['listaDesplegableUpdate'],
                            ],
                            [
                                'controllers' => ['admin/listas-desplegables'],
                                'actions'     => ['delete-ajax'],
                                'allow'       => true,
                                'roles'       => ['listaDesplegableDelete'],
                            ],
                        // Configuraciones
                            [
                                'controllers' => ['admin/setting'],
                                'actions' => ['parametros', 'parametos-json-btt'],
                                'allow' => true,
                                'roles' => ['parametrosView'],
                            ],
                            [
                                'controllers' => ['admin/setting'],
                                'actions' => ['parametros-update'],
                                'allow' => true,
                                'roles' => ['parametrosUpdate'],
                            ],
                        // Configuraciones del sitio
                            [
                                'controllers' => ['admin/configuracion'],
                                'actions' => ['configuracion-update','update-credenciales'],
                                'allow' => true,
                                'roles' => ['configuracionSitio'],
                            ],
                            [
                                'controllers' => ['admin/configuracion'],
                                'actions' => ['precio-libra-ajax'],
                                'allow' => true,
                                'roles' => ['@'],
                            ],
                        // Historial de acceso
                            [
                                'controllers' => ['admin/historial-de-acceso'],
                                'actions' => ['index', 'historial-de-accesos-json-btt'],
                                'allow' => true,
                                'roles' => ['historialAccesosUser'],
                            ],


                    /*************************
                    * Crm
                    *************************/
                        // Clientes
                            [
                                'controllers' => ['crm/cliente'],
                                'actions' => ['index', 'clientes-json-btt', 'remover-file', 'add-files-tutor', 'view', 'historial-cambios','cliente-ajax','create-alumno','create-credito','alumno-data','edit-alumno','edit-form-alumno','delete-alumno'],
                                'allow' => true,
                                'roles' => ['padreTutorView'],
                            ],
                            [
                                'controllers' => ['crm/cliente'],
                                'actions' => ['create','cliente-create-ajax','cliente-codigo-ajax'],
                                'allow' => true,
                                'roles' => ['padreTutorCreate'],
                            ],
                            [
                                'controllers' => ['crm/cliente'],
                                'actions' => ['update'],
                                'allow' => true,
                                'roles' => ['padreTutorUpdate'],
                            ],
                            [
                                'controllers' => ['crm/cliente'],
                                'actions' => ['delete'],
                                'allow' => true,
                                'roles' => ['padreTutorDelete'],
                            ],

                    /*************************
                    * Alumnos
                    *************************/
                            [
                                'controllers' => ['alumnos/alumno'],
                                'actions' => ['index', 'alumnos-json-btt', 'view', 'add-files-alumno','add-files', 'print-ficha', 'print-carta-compromiso','remover-file','update-alumno'],
                                'allow' => true,
                                'roles' => ['alumnosView'],
                            ],

                            [
                                'controllers' => ['alumnos/alumno'],
                                'actions' => ['update','activar-alumno'],
                                'allow' => true,
                                'roles' => ['alumnosUpdate'],
                            ],

                            [
                                'controllers' => ['alumnos/alumno'],
                                'actions' => ['cancel'],
                                'allow' => true,
                                'roles' => ['alumnosCancel'],
                            ],


                    /*************************
                    * Operacion
                    *************************/
                            [
                                'controllers' => ['gestion/caja'],
                                'actions' => ['index', 'cajas-json-btt', 'view','imprimir-ticket','verificar-tipo','verificar-meses', 'get-meses', 'get-confirm-meses', 'registro-pago'],
                                'allow' => true,
                                'roles' => ['cajaView'],
                            ],
                            [
                                'controllers' => ['gestion/caja'],
                                'actions' => ['create','padre-alumno-all','alumno-info', 'get-tipo','guardar-pago'],
                                'allow' => true,
                                'roles' => ['cajaCreate'],
                            ],
                            [
                                'controllers' => ['gestion/caja'],
                                'actions' => ['update'],
                                'allow' => true,
                                'roles' => ['cajaUpdate'],
                            ],

                    /*************************
                    * Documentacion
                    *************************/
                            [
                                'controllers' => ['gestion/documento'],
                                'actions' => ['index', 'documentos-json-btt', 'view'],
                                'allow' => true,
                                'roles' => ['documentoView'],
                            ],
                            [
                                'controllers' => ['gestion/documento'],
                                'actions' => ['create'],
                                'allow' => true,
                                'roles' => ['documentoCreate'],
                            ],
                            [
                                'controllers' => ['gestion/documento'],
                                'actions' => ['update'],
                                'allow' => true,
                                'roles' => ['documentoUpdate'],
                            ],
                    /*************************
                    * Lista
                    *************************/
                            [
                                'controllers' => ['gestion/lista'],
                                'actions' => ['index','listas-json-btt','view', 'print'],
                                'allow' => true,
                                'roles' => ['listaView'],
                            ],
                            [
                                'controllers' => ['gestion/lista'],
                                'actions' => ['create'],
                                'allow' => true,
                                'roles' => ['listaCreate'],
                            ],
                            [
                                'controllers' => ['gestion/lista'],
                                'actions' => ['update'],
                                'allow' => true,
                                'roles' => ['listaUpdate'],
                            ],
                            [
                                'controllers' => ['gestion/lista'],
                                'actions' => ['delete'],
                                'allow' => true,
                                'roles' => ['listaDelete'],
                            ],
                    /*************************
                    * Articulos
                    *************************/
                            [
                                'controllers' => ['gestion/articulo'],
                                'actions' => ['index', 'articulos-json-btt', 'view', 'articulos-ajax'],
                                'allow' => true,
                                'roles' => ['articuloView'],
                            ],
                            [
                                'controllers' => ['gestion/articulo'],
                                'actions' => ['create'],
                                'allow' => true,
                                'roles' => ['articuloCreate'],
                            ],
                            [
                                'controllers' => ['gestion/articulo'],
                                'actions' => ['update'],
                                'allow' => true,
                                'roles' => ['articuloUpdate'],
                            ],
                            [
                                'controllers' => ['gestion/articulo'],
                                'actions' => ['delete'],
                                'allow' => true,
                                'roles' => ['articuloDelete'],
                            ],
                    /*************************
                    * Ciclo
                    *************************/
                            [
                                'controllers' => ['gestion/ciclo'],
                                'actions' => ['index', 'ciclo-json-btt', 'view', 'create','update','tarifas','update-tarifas'],
                                'allow' => true,
                                'roles' => ['articuloView'],
                            ],
                    /*************************
                    * Agenda
                    *************************/
                            [
                                'controllers' => ['calendario/agenda'],
                                'actions' => ['recordatorio','add-agenda','get-agenda','get-evento','delete-event'],
                                'allow' => true,
                                'roles' => ['calendario'],
                            ],

                ], // rules
            ], // access
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout'      => ['post'],
                    'create-ajax' => ['post'],
                    'update-ajax' => ['post'],
                    'sort-ajax'   => ['put'],
                    'cancel-ajax' => ['post'],
                    'delete-ajax' => ['delete'],
                ],
            ], // verbs
        ]; // return
    } // behaviors

} // AppController
