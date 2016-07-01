var CONFIG;

(function() {

var appPrefix = '/';
var templateUrlPrefix = 'app/templates/';
var appVersion = 8;

CONFIG = {
  appName:"Banner Management",
  bannerImageFolderName:"contentfiles",
  version : appVersion,
  dateFormat: 'dd-MM-yyyy hh:mm:ss',
  baseDirectory : appPrefix,
  templateDirectory : templateUrlPrefix,
  templateFileQuerystring : "?v=" + appVersion,
    local_api_base_url: 'http://localhost/bannermanage/',
    dev_api_base_url: 'http://192.168.1.174:4070/bannermanage/',
    staging_api_base_url: 'http://192.168.1.159/',
    
  getApiUrl : function(url)
  {                        
      
    if (window.location.host == "localhost:8888")
    {


        return CONFIG.local_api_base_url +"bannerapi/v1/" + url;
    }
    else if(window.location.host == '192.168.1.159')
    {
        return CONFIG.staging_api_base_url+"bannerapi/v1/index.php/" + url;
    }
    else
    {
        return CONFIG.dev_api_base_url+"bannerapi/v1/" + url;
    }
    
  },
  getUploadUrl : function(name)
  {                             
    if (window.location.host == "localhost:8888")
    {
        return CONFIG.local_api_base_url + CONFIG.bannerImageFolderName+"/" + name;
    }
    else  if (window.location.host == "192.168.1.159")
    {
        return CONFIG.staging_api_base_url+CONFIG.bannerImageFolderName+"/" + name;
        
    }
    else
    {
        return CONFIG.dev_api_base_url+CONFIG.bannerImageFolderName+"/" + name;
    }
  },
  
  routing : {

    prefix : '',
    html5Mode : false

  },

  viewUrlPrefix : templateUrlPrefix + 'views/',
  partialUrlPrefix : templateUrlPrefix + 'partials/',

  templateFileSuffix : '_tpl.html',

  prepareViewTemplateUrl : function(url) {
    return this.viewUrlPrefix + url + this.templateFileSuffix + this.templateFileQuerystring;
  },
  getAppName : function ()
  {
      return this.appName;
  }
  
};

})();
