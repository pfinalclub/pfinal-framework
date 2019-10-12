<?php
	/**
	 * Created by PhpStorm.
	 * User: 南丞
	 * Date: 2019/3/4
	 * Time: 14:35
	 *
	 *
	 *                      _ooOoo_
	 *                     o8888888o
	 *                     88" . "88
	 *                     (| ^_^ |)
	 *                     O\  =  /O
	 *                  ____/`---'\____
	 *                .'  \\|     |//  `.
	 *               /  \\|||  :  |||//  \
	 *              /  _||||| -:- |||||-  \
	 *              |   | \\\  -  /// |   |
	 *              | \_|  ''\---/''  |   |
	 *              \  .-\__  `-`  ___/-. /
	 *            ___`. .'  /--.--\  `. . ___
	 *          ."" '<  `.___\_<|>_/___.'  >'"".
	 *        | | :  `- \`.;`\ _ /`;.`/ - ` : | |
	 *        \  \ `-.   \_ __\ /__ _/   .-` /  /
	 *  ========`-.____`-.___\_____/___.-`____.-'========
	 *                       `=---='
	 *  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	 *           佛祖保佑       永无BUG     永不修改
	 *
	 */
	
	namespace pf\framework\build;
	
	class Base
	{
		//应用已启动
		protected $booted = false;
		//延迟加载服务提供者
		protected $deferProviders = [];
		//已加载服务提供者
		protected $serviceProviders = [];
		//系统服务
		protected $providers = [];
		//外观别名
		protected $facades = [];
		
		public function bootstrap()
		{
			$this->services();
			spl_autoload_register([$this, 'autoload']);
			
		}
		
		public function services()
		{
			defined('ROOT_PATH') or define('ROOT_PATH', '');
			$servers = require __DIR__.'/../build/service.php';
			$users = require ROOT_PATH.'/system/config/service.php';
			$this->providers = array_merge($servers['providers'], $users['providers']);
			$this->facades = array_merge($servers['facades'], $users['facades']);
		}
		
		public function autoload($class)
		{
			//通过外观类加载系统服务
			$facade = str_replace('\\', '/', $class);
			if (isset($this->facades[$facade])) {
				//加载facade类
				class_alias($this->facades[$facade], $class);
			}
		}
		
		protected function boot()
		{
			if ($this->booted) {
				return;
			}
			foreach ($this->serviceProviders as $p) {
				$this->bootProvider($p);
			}
			$this->booted = true;
		}
		
		/**
		 * 运行服务提供者的boot方法
		 * @param $provider
		 */
		protected function bootProvider($provider)
		{
			if (method_exists($provider, 'boot')) {
				$provider->boot($this);
			}
		}
	}
