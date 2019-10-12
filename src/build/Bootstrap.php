<?php
	/**
	 * ----------------------------------------
	 * | Created By pfinal-framework                 |
	 * | User: pfinal <lampxiezi@163.com>     |
	 * | Date: 2019/10/12                      |
	 * | Time: 下午4:06                        |
	 * ----------------------------------------
	 * |    _____  ______ _             _     |
	 * |   |  __ \|  ____(_)           | |    |
	 * |   | |__) | |__   _ _ __   __ _| |    |
	 * |   |  ___/|  __| | | '_ \ / _` | |    |
	 * |   | |    | |    | | | | | (_| | |    |
	 * |   |_|    |_|    |_|_| |_|\__,_|_|    |
	 * ----------------------------------------
	 */
	
	namespace pf\framework\build;
	
	use pf\config\Config;
	
	trait Bootstrap
	{
		protected function runApp()
		{
			if (RUN_MODE == 'HTTP') {
				//解析路由
				require ROOT_PATH.'/system/routes.php';
				//执行全局中间件
				# $this->middleware(Config::get('middleware.global'));
				//分配闪存错误信息
				$this->withErrors();
				//模板文件处理中间件
				// Middleware::add('view_parse_file', [ViewParseFile::class]);
				//执行路由或控制器方法
				$content = Route::bootstrap()->exec();
				echo is_object($content) ? $content : Response::make($content);
			}
		}
		
		/**
		 * 分配闪存错误信息
		 */
		protected function withErrors()
		{
			//分配SESSION闪存中的错误信息
			View::with('errors', Session::flash('errors'));
			if ($post = Request::post()) {
				Session::flash('oldFormData', $post);
			}
		}
	}