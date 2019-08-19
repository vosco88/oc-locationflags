<?php namespace Vosco\LocationFlags;

use Backend;
use System\Classes\PluginBase;

use RainLab\Location\Models\Country as Country;
use RainLab\Location\Controllers\Locations as Locations;

/**
 * LocationFlags Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'vosco.locationflags::lang.plugin.name',
            'description' => 'vosco.locationflags::lang.plugin.description',
            'author'      => 'JanVoscek',
            'icon'        => 'icon-map-marker'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
		Country::extend(function($model) {
			$model->attachOne['flag'] = ['System\Models\File'];
		});

		Locations::extendListColumns(function($list, $model) {

			if (!$model instanceof Country)
				return;

			$list->addColumns([
				'flag' => [
					'label' => 'vosco.locationflags::lang.flag',
                    'sortable' => false,
                    'searchable' => false,
                    'invisible' => 'true',
                    'type' => 'partial',
                    'path' => '~/plugins/vosco/locationflags/models/country/_flag_column.htm',
                    'width' => '5%'
				]
			]);

		});

		Locations::extendFormFields(function($form, $model, $context)
{
		if (!$model instanceof Country) {
			return;
		}

		$form->addFields([
			'flag' => [
				'label'   => 'vosco.locationflags::lang.flag',
                'comment' => 'vosco.locationflags::lang.fields.comment',
                'align' => 'auto',
				'mode' => 'image',
				'type' => 'fileupload'
			],
		]);

		});
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Vosco\LocationFlags\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'vosco.locationflags.some_permission' => [
                'tab' => 'LocationFlags',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        return [
            'locationflags' => [
                'label'       => 'rainlab.location::lang.locations.menu_label',
                'url'         => Backend::url('rainlab/location/locations'),
                'icon'        => 'icon-globe',
                'permissions' => ['rainlab.location.access_settings'],
                'order'       => 500,
            ],
        ];
    }
}
