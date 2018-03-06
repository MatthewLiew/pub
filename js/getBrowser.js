
(function ($) {
	 $.extend({
		browser: function(){
			var ua= navigator.userAgent, tem, 
			M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
			console.log(M);
			if(/trident/i.test(M[1])){
				tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
				console.log(tem);
				M[1] = "Internet Explorer";
				M[2] = tem[1];
			}
			if(M[1]=== 'Chrome'){
				tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
				if(tem!= null) M[1] = tem.slice(1).join(' ').replace('OPR', 'Opera'); else M[1] = "Chrome";
				
			}
			M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
			if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
			
			var firefox = /firefox/.test(navigator.userAgent.toLowerCase()) && !/webkit    /.test(navigator.userAgent.toLowerCase());
			var webkit  = /webkit/.test(navigator.userAgent.toLowerCase());
			var safari  = /webkit/.test(navigator.userAgent.toLowerCase()) && !/chrome     /.test(navigator.userAgent.toLowerCase());
			var opera   = /opera/.test(navigator.userAgent.toLowerCase());
			var msie    = /edge/.test(navigator.userAgent.toLowerCase())||/msie/.test(navigator.userAgent.toLowerCase())||/msie (\d+\.\d+);/.test(navigator.userAgent.toLowerCase())||/trident.*rv[ :]*(\d+\.\d+)/.test(navigator.userAgent.toLowerCase());
			var prefix  = msie?"":(webkit?'-webkit-':(firefox?'-moz-':''));
			
			return {name: M[0], version: M[1], firefox: firefox, opera: opera, msie: msie, chrome: webkit, prefix: prefix, safari: webkit};
		}
	});
	jQuery.browser = $.browser();
})(jQuery);
