<?php
namespace LecAdmin;

class filemanager
{

	/* Configuration */
		
		//root directory
		private $_root_directory = '';
		
		//folders
		public $_hiddenFolders = [];
		public $_universalFolders = [];
		
		//files
		private $_ajax_file = '/do/response/filemanager/ajax';
		private $_download_serve_file = '/do/response/filemanager/download';
		
		//templates
		private $_filemanager_wrapper_template = '/view/lec-admin/template/includes/filemanager/wrapper.php'; 
		private $_filemanager_top_bar_template = '/view/lec-admin/template/includes/filemanager/top_bar.php'; 
		private $_filemanager_contents_template = '/view/lec-admin/template/includes/filemanager/content.php';
		
		//error messages
		private $_generic_error_message = 'Oops, something went wrong!';
		
		//properties
		private $_allowed_file_types = array(
			'xls',
			'xlsx',
			'doc',
			'docx',
			'pdf',
			'jpg',
			'jpeg',
			'png',
			'gif',
			'bmp',
			'xml',
			'sss',
			'zip',
			'rar',
			'tar',
			'7z',
			'tar.gz',
			'csv',
			'sav',
			'txt'
		);
		
	/**
	* Filemanager Construct
	*
	* @param string $rootDir the directory of the filemanager files
	*/
		function __construct(string $rootDir = '')
		{
			
			
			$this->_root_directory = $rootDir;
			if(!file_exists(DOC_ROOT.$this->_root_directory)){
				throw new \Exception('Can\'t find the filemanager directory.');
			}
		}
		
	/* HTML  function */
	
		
		/**
		* Main launch function for filemanager
		*
		* @return void
		*/
			public function launchFileManagerHTML(): void
			{
				
				/* Main call function */
				$directory = DOC_ROOT.$this->_root_directory;
				//go to output of html
				include(DOC_ROOT.$this->_filemanager_wrapper_template);
				
			}
		
		/**
		* Output directory contents HTML
		*
		* @param string $directory Directory Path
		*
		* @return void
		*/
			public function loadDirectoryContentsHTML(string $directory = ''):void
			{ 
				$dirContents = $this->getDirectoryContents($directory);
				include(DOC_ROOT.$this->_filemanager_contents_template);
				return;
			}
		
		/**
		* Output breadcrumb HTML
		*
		* @param string $directory Directory Path
		*
		* @return string
		*/
			public function getBreadcrumb(string $directory = ''): string
			{
				
				

				if ($directory ==  DOC_ROOT.$this->_root_directory){
					return $r = '<span class="fm_change_directory fm_pointer" data-dir="'.DOC_ROOT.$this->_root_directory.'"><i class="fa fa-home fa-lg" style="line-height:40px;"></i></span>';
				} else {
					
					//get rid of root directory
					$directory = str_replace(DOC_ROOT.$this->_root_directory,'',$directory);
					
					//explode into further bitys
					$directory = explode('/', rtrim($directory, '/'));
					
					$r = '<span class="fm_change_directory fm_pointer" data-dir="'.DOC_ROOT.$this->_root_directory.'"><i class="fa fa-home fa-lg"></i></span>';
					
					$runningString = DOC_ROOT.$this->_root_directory;
					$i = 1;
					foreach ($directory as $dirPart){
						
						$dirPartName = ($i < count($directory)) ? '<b>'.$dirPart.'</b>' : $dirPart ;
						$runningString .= $dirPart.'/';
						$r .= ' / <span class="fm_change_directory fm_pointer" data-dir="'.urlencode($runningString).'">'.$dirPartName.'</span>';
						$i ++;
					}
					return $r;
				
				}
				
			}
		
		/**
		* Output Back Button HTML
		*
		* @param string $directory Directory Path
		*
		* @return string
		*/
			public function getBackButton($directory): string
			{
				
				if ($directory ==  DOC_ROOT.$this->_root_directory){
					return '';
				} else {
					
					$directory = explode('/', trim($directory, '/'));
					unset($directory[(count($directory) -1)]);
					$backDir = '/'.implode('/', $directory).'/';
					return $r = '<div class="fm_back fm_change_directory fm_pointer" data-dir="'.urlencode($backDir).'"><i class="fa fa-arrow-left fa-lg fa-fw"></i> Back</div>';
					
				}
				
			}
		
		/**
		* Get Font Awesome Icon
		*
		* @param string $file Filename
		*
		* @return string
		*/
			public function getFAIconFile(string $file = ''): string
			{
				
				$file = explode('.', $file);
				$ext = end($file);
				
				switch($ext){
					case 'xls':
						return 'file-excel-o';
					break;
					case 'xlsx':
						return 'file-excel-o';
					break;
					case 'doc':
						return 'file-word-o';
					break;
					case 'docx':
						return 'file-word-o';
					break;
					case 'pdf':
						return 'file-pdf-o';
					break;
					case 'jpg':
						return 'file-image-o';
					break;
					case 'jpeg':
						return 'file-image-o';
					break;
					case 'png':
						return 'file-image-o';
					break;
					case 'gif':
						return 'file-image-o';
					break;
					case 'bmp':
						return 'file-image-o';
					break;
					case 'xml':
						return 'file-code-o';
					break;
					case 'sss':
						return 'file-code-o';
					break;
					case 'zip':
						return 'file-archive-o';
					break;
					case 'rar':
						return 'file-archive-o';
					break;
					case 'tar':
						return 'file-archive-o';
					break;
					case '7z':
						return 'file-archive-o';
					break;
					case 'tar.gz':
						return 'file-archive-o';
					break;
					case 'csv':
						return 'file-text-o';
					break;
					case 'sav':
						return 'file-text-o';
					break;
					case 'txt':
						return 'file-text-o';
					break;
					default:
						return 'file';
					break;
				}
				
			}
			
	/* END HTML  function */
	
	/* UTITLITY  function */
			
		public function getDirArray($dir){
			
			$contents = [];
			
			foreach (@scandir($dir) as $node) {
				
				if ($node == '.' || $node == '..'){
					continue;
				}
				
				if (is_dir(rtrim($dir, '/') . '/' . trim($node, '/'))) {
					
					$contents[rtrim($dir, '/').'/'.trim($node, '/')] = $this->getDirArray(rtrim($dir, '/') . '/' . trim($node, '/') . '/');
				}
				
			}
			
			return $contents;
			
		}
	
		public function getDirectoryContents(string $directory = ''){
			
			/* Returns array of files + folders */
			
			//if no directory poassed, revert to root
			if ($directory === ''){
				$directory = DOC_ROOT.$this->_root_directory;
			}
			
			//scan root folder...
			$directoryArray = @scandir($directory);
			
			if ($directoryArray === false){
				//not a directory
				throw new \Exception('Directory Requested Not Valid.');
			}
			
			//set up arrays for files and folders
			$fileArray = array();
			$folderArray = array();
			
			foreach ($directoryArray as $objectName){
				
				if (is_dir($directory.$objectName) && !preg_match('#^[\.]+$#', $objectName)){
					
					$folderArray[] = $objectName;
					
				} else {
					
					//if level up markers
					if (preg_match('#^[\.]+$#', $objectName) || $objectName == '.htaccess'|| $objectName == '.ftpquota'){
						//ignore
					} else {
						$fileArray[] = $objectName;
					}
					
				}
				
			}
			
			//sort arrays into alphabetical
			sort($fileArray);
			sort($folderArray);
			
			return ['folders'=>$folderArray,'files'=>$fileArray];
			
		}
		
		public function fileSizeHuman($bytes, $decimals = 2) {
		  $sz = 'BKMGTP';
		  $factor = floor((strlen($bytes) - 1) / 3);
		  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
		}
		
		public function makeNewDirectory($folderName, $directory){
			
				//clean folder name
				$folderName = preg_replace('#[^0-9a-zA-Z\_\-\.]#', '', $folderName);
				
				if (!is_dir($directory.$folderName)){
					
					mkdir($directory.$folderName);
					
					//chmod otherwise htaccess wont work
					chmod($directory.$folderName, 0774);
					
					$myfile = fopen($directory.$folderName.'/.htaccess', "w");
					$txt = 'Authname Private'.PHP_EOL.'AuthType basic'.PHP_EOL.'require user noadmittance';
					fwrite($myfile, $txt);
					fclose($myfile);
					
					return true;
					
				} else {
					return false;
				}
			
		}
		
		public function deleteFolder($folder){
			
			//get directory contents
			$directoryArray = @scandir($folder);
			
			if ($directoryArray === false){
				//not a directory
				return false;
			}
			
			//set up arrays for files and folders
			$fileArray = array();
			$folderArray = array();
			
			foreach ($directoryArray as $objectName){
				
				if (is_dir($folder.$objectName) && !preg_match('#^[\.]+$#', $objectName)){
					$folderArray[] = $objectName;
				} else {
					//if level up markers
					if (preg_match('#^[\.]+$#', $objectName)){
						//ignore
					} else {
						$fileArray[] = $objectName;
					}
				}
			}
						
			if (count($fileArray) == 1 && count($folderArray) == 0 && in_array('.htaccess', $fileArray)){
				//echo $folder.'.htaccess';
				unlink($folder.'.htaccess');
			}
			
			$delete = @rmdir($folder);
			if ($delete === false){
				return false;
			} else {
				
				return true;
			}
			
		}
		
		public function uploadFile($directory){
			
			$directory = urldecode($directory);
			
			if ($_FILES['fm_upload_file']["error"] > 0){
				return '{"files": [
				  {
					"name": "'.$_FILES['fm_upload_file']["name"].'",
					"size": 902604,
					"error": "Errors in upload"
				  }
				]}'; exit;
			} else {
				$nameTemp = explode(".", $_FILES['fm_upload_file']["name"]);
				$extension = strtolower(end($nameTemp));
				
				if (in_array($extension, $this->_allowed_file_types) ){
				
					if (move_uploaded_file($_FILES['fm_upload_file']["tmp_name"], $directory.$_FILES['fm_upload_file']["name"])){
													
						return '{"files": [
						  {
							"name": "'.$_FILES['fm_upload_file']["name"].'",
							"size": '.filesize($directory.$_FILES['fm_upload_file']["name"]).',
							"url": "",
							"thumbnailUrl": "",
							"deleteUrl": "",
							"deleteType": "DELETE"
						  }
						]}';exit;
						
					} else {
						return '{"files": [
						  {
							"name": "'.$_FILES['fm_upload_file']["name"].'",
							"size": 902604,
							"error": "File could not be moved from temporary directory"
						  }
						]}'; exit;
					}
					
				} else {
					return '{"files": [
					  {
						"name": "'.$_FILES['fm_upload_file']["name"].'",
						"size": 902604,
						"error": "File Type Not Allowed"
					  }
					]}'; exit;
				}
			}
			
		}
		
		public function isImage($file){
			
			$nameTemp = explode(".", $file);
			$extension = strtolower(end($nameTemp));
			
			if ($extension == 'jpg' ||$extension == 'jpeg' ||$extension == 'png' || $extension == 'gif' || $extension == 'bmp'){
				return true;
			} else {
				return false;
			}
			
		}
		
	/* END UTILITY  function */
	
	/* JS Function */
	
		public function getJS(){
			
			?>
				
				<script>
					
					//ajax upload
					!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery","jquery.ui.widget"],e):"object"==typeof exports?e(require("jquery"),require("./vendor/jquery.ui.widget")):e(window.jQuery)}(function(e){"use strict";function t(t){var i="dragover"===t;return function(r){r.dataTransfer=r.originalEvent&&r.originalEvent.dataTransfer;var n=r.dataTransfer;n&&-1!==e.inArray("Files",n.types)&&this._trigger(t,e.Event(t,{delegatedEvent:r}))!==!1&&(r.preventDefault(),i&&(n.dropEffect="copy"))}}e.support.fileInput=!(new RegExp("(Android (1\\.[0156]|2\\.[01]))|(Windows Phone (OS 7|8\\.0))|(XBLWP)|(ZuneWP)|(WPDesktop)|(w(eb)?OSBrowser)|(webOS)|(Kindle/(1\\.0|2\\.[05]|3\\.0))").test(window.navigator.userAgent)||e('<input type="file">').prop("disabled")),e.support.xhrFileUpload=!(!window.ProgressEvent||!window.FileReader),e.support.xhrFormDataFileUpload=!!window.FormData,e.support.blobSlice=window.Blob&&(Blob.prototype.slice||Blob.prototype.webkitSlice||Blob.prototype.mozSlice),e.widget("blueimp.fileupload",{options:{dropZone:e(document),pasteZone:void 0,fileInput:void 0,replaceFileInput:!0,paramName:void 0,singleFileUploads:!0,limitMultiFileUploads:void 0,limitMultiFileUploadSize:void 0,limitMultiFileUploadSizeOverhead:512,sequentialUploads:!1,limitConcurrentUploads:void 0,forceIframeTransport:!1,redirect:void 0,redirectParamName:void 0,postMessage:void 0,multipart:!0,maxChunkSize:void 0,uploadedBytes:void 0,recalculateProgress:!0,progressInterval:100,bitrateInterval:500,autoUpload:!0,messages:{uploadedBytes:"Uploaded bytes exceed file size"},i18n:function(t,i){return t=this.messages[t]||t.toString(),i&&e.each(i,function(e,i){t=t.replace("{"+e+"}",i)}),t},formData:function(e){return e.serializeArray()},add:function(t,i){return t.isDefaultPrevented()?!1:void((i.autoUpload||i.autoUpload!==!1&&e(this).fileupload("option","autoUpload"))&&i.process().done(function(){i.submit()}))},processData:!1,contentType:!1,cache:!1,timeout:0},_specialOptions:["fileInput","dropZone","pasteZone","multipart","forceIframeTransport"],_blobSlice:e.support.blobSlice&&function(){var e=this.slice||this.webkitSlice||this.mozSlice;return e.apply(this,arguments)},_BitrateTimer:function(){this.timestamp=Date.now?Date.now():(new Date).getTime(),this.loaded=0,this.bitrate=0,this.getBitrate=function(e,t,i){var r=e-this.timestamp;return(!this.bitrate||!i||r>i)&&(this.bitrate=(t-this.loaded)*(1e3/r)*8,this.loaded=t,this.timestamp=e),this.bitrate}},_isXHRUpload:function(t){return!t.forceIframeTransport&&(!t.multipart&&e.support.xhrFileUpload||e.support.xhrFormDataFileUpload)},_getFormData:function(t){var i;return"function"===e.type(t.formData)?t.formData(t.form):e.isArray(t.formData)?t.formData:"object"===e.type(t.formData)?(i=[],e.each(t.formData,function(e,t){i.push({name:e,value:t})}),i):[]},_getTotal:function(t){var i=0;return e.each(t,function(e,t){i+=t.size||1}),i},_initProgressObject:function(t){var i={loaded:0,total:0,bitrate:0};t._progress?e.extend(t._progress,i):t._progress=i},_initResponseObject:function(e){var t;if(e._response)for(t in e._response)e._response.hasOwnProperty(t)&&delete e._response[t];else e._response={}},_onProgress:function(t,i){if(t.lengthComputable){var r,n=Date.now?Date.now():(new Date).getTime();if(i._time&&i.progressInterval&&n-i._time<i.progressInterval&&t.loaded!==t.total)return;i._time=n,r=Math.floor(t.loaded/t.total*(i.chunkSize||i._progress.total))+(i.uploadedBytes||0),this._progress.loaded+=r-i._progress.loaded,this._progress.bitrate=this._bitrateTimer.getBitrate(n,this._progress.loaded,i.bitrateInterval),i._progress.loaded=i.loaded=r,i._progress.bitrate=i.bitrate=i._bitrateTimer.getBitrate(n,r,i.bitrateInterval),this._trigger("progress",e.Event("progress",{delegatedEvent:t}),i),this._trigger("progressall",e.Event("progressall",{delegatedEvent:t}),this._progress)}},_initProgressListener:function(t){var i=this,r=t.xhr?t.xhr():e.ajaxSettings.xhr();r.upload&&(e(r.upload).bind("progress",function(e){var r=e.originalEvent;e.lengthComputable=r.lengthComputable,e.loaded=r.loaded,e.total=r.total,i._onProgress(e,t)}),t.xhr=function(){return r})},_isInstanceOf:function(e,t){return Object.prototype.toString.call(t)==="[object "+e+"]"},_initXHRData:function(t){var i,r=this,n=t.files[0],o=t.multipart||!e.support.xhrFileUpload,s="array"===e.type(t.paramName)?t.paramName[0]:t.paramName;t.headers=e.extend({},t.headers),t.contentRange&&(t.headers["Content-Range"]=t.contentRange),o&&!t.blob&&this._isInstanceOf("File",n)||(t.headers["Content-Disposition"]='attachment; filename="'+encodeURI(n.name)+'"'),o?e.support.xhrFormDataFileUpload&&(t.postMessage?(i=this._getFormData(t),t.blob?i.push({name:s,value:t.blob}):e.each(t.files,function(r,n){i.push({name:"array"===e.type(t.paramName)&&t.paramName[r]||s,value:n})})):(r._isInstanceOf("FormData",t.formData)?i=t.formData:(i=new FormData,e.each(this._getFormData(t),function(e,t){i.append(t.name,t.value)})),t.blob?i.append(s,t.blob,n.name):e.each(t.files,function(n,o){(r._isInstanceOf("File",o)||r._isInstanceOf("Blob",o))&&i.append("array"===e.type(t.paramName)&&t.paramName[n]||s,o,o.uploadName||o.name)})),t.data=i):(t.contentType=n.type||"application/octet-stream",t.data=t.blob||n),t.blob=null},_initIframeSettings:function(t){var i=e("<a></a>").prop("href",t.url).prop("host");t.dataType="iframe "+(t.dataType||""),t.formData=this._getFormData(t),t.redirect&&i&&i!==location.host&&t.formData.push({name:t.redirectParamName||"redirect",value:t.redirect})},_initDataSettings:function(e){this._isXHRUpload(e)?(this._chunkedUpload(e,!0)||(e.data||this._initXHRData(e),this._initProgressListener(e)),e.postMessage&&(e.dataType="postmessage "+(e.dataType||""))):this._initIframeSettings(e)},_getParamName:function(t){var i=e(t.fileInput),r=t.paramName;return r?e.isArray(r)||(r=[r]):(r=[],i.each(function(){for(var t=e(this),i=t.prop("name")||"files[]",n=(t.prop("files")||[1]).length;n;)r.push(i),n-=1}),r.length||(r=[i.prop("name")||"files[]"])),r},_initFormSettings:function(t){t.form&&t.form.length||(t.form=e(t.fileInput.prop("form")),t.form.length||(t.form=e(this.options.fileInput.prop("form")))),t.paramName=this._getParamName(t),t.url||(t.url=t.form.prop("action")||location.href),t.type=(t.type||"string"===e.type(t.form.prop("method"))&&t.form.prop("method")||"").toUpperCase(),"POST"!==t.type&&"PUT"!==t.type&&"PATCH"!==t.type&&(t.type="POST"),t.formAcceptCharset||(t.formAcceptCharset=t.form.attr("accept-charset"))},_getAJAXSettings:function(t){var i=e.extend({},this.options,t);return this._initFormSettings(i),this._initDataSettings(i),i},_getDeferredState:function(e){return e.state?e.state():e.isResolved()?"resolved":e.isRejected()?"rejected":"pending"},_enhancePromise:function(e){return e.success=e.done,e.error=e.fail,e.complete=e.always,e},_getXHRPromise:function(t,i,r){var n=e.Deferred(),o=n.promise();return i=i||this.options.context||o,t===!0?n.resolveWith(i,r):t===!1&&n.rejectWith(i,r),o.abort=n.promise,this._enhancePromise(o)},_addConvenienceMethods:function(t,i){var r=this,n=function(t){return e.Deferred().resolveWith(r,t).promise()};i.process=function(t,o){return(t||o)&&(i._processQueue=this._processQueue=(this._processQueue||n([this])).pipe(function(){return i.errorThrown?e.Deferred().rejectWith(r,[i]).promise():n(arguments)}).pipe(t,o)),this._processQueue||n([this])},i.submit=function(){return"pending"!==this.state()&&(i.jqXHR=this.jqXHR=r._trigger("submit",e.Event("submit",{delegatedEvent:t}),this)!==!1&&r._onSend(t,this)),this.jqXHR||r._getXHRPromise()},i.abort=function(){return this.jqXHR?this.jqXHR.abort():(this.errorThrown="abort",r._trigger("fail",null,this),r._getXHRPromise(!1))},i.state=function(){return this.jqXHR?r._getDeferredState(this.jqXHR):this._processQueue?r._getDeferredState(this._processQueue):void 0},i.processing=function(){return!this.jqXHR&&this._processQueue&&"pending"===r._getDeferredState(this._processQueue)},i.progress=function(){return this._progress},i.response=function(){return this._response}},_getUploadedBytes:function(e){var t=e.getResponseHeader("Range"),i=t&&t.split("-"),r=i&&i.length>1&&parseInt(i[1],10);return r&&r+1},_chunkedUpload:function(t,i){t.uploadedBytes=t.uploadedBytes||0;var r,n,o=this,s=t.files[0],a=s.size,l=t.uploadedBytes,p=t.maxChunkSize||a,u=this._blobSlice,d=e.Deferred(),h=d.promise();return this._isXHRUpload(t)&&u&&(l||a>p)&&!t.data?i?!0:l>=a?(s.error=t.i18n("uploadedBytes"),this._getXHRPromise(!1,t.context,[null,"error",s.error])):(n=function(){var i=e.extend({},t),h=i._progress.loaded;i.blob=u.call(s,l,l+p,s.type),i.chunkSize=i.blob.size,i.contentRange="bytes "+l+"-"+(l+i.chunkSize-1)+"/"+a,o._initXHRData(i),o._initProgressListener(i),r=(o._trigger("chunksend",null,i)!==!1&&e.ajax(i)||o._getXHRPromise(!1,i.context)).done(function(r,s,p){l=o._getUploadedBytes(p)||l+i.chunkSize,h+i.chunkSize-i._progress.loaded&&o._onProgress(e.Event("progress",{lengthComputable:!0,loaded:l-i.uploadedBytes,total:l-i.uploadedBytes}),i),t.uploadedBytes=i.uploadedBytes=l,i.result=r,i.textStatus=s,i.jqXHR=p,o._trigger("chunkdone",null,i),o._trigger("chunkalways",null,i),a>l?n():d.resolveWith(i.context,[r,s,p])}).fail(function(e,t,r){i.jqXHR=e,i.textStatus=t,i.errorThrown=r,o._trigger("chunkfail",null,i),o._trigger("chunkalways",null,i),d.rejectWith(i.context,[e,t,r])})},this._enhancePromise(h),h.abort=function(){return r.abort()},n(),h):!1},_beforeSend:function(e,t){0===this._active&&(this._trigger("start"),this._bitrateTimer=new this._BitrateTimer,this._progress.loaded=this._progress.total=0,this._progress.bitrate=0),this._initResponseObject(t),this._initProgressObject(t),t._progress.loaded=t.loaded=t.uploadedBytes||0,t._progress.total=t.total=this._getTotal(t.files)||1,t._progress.bitrate=t.bitrate=0,this._active+=1,this._progress.loaded+=t.loaded,this._progress.total+=t.total},_onDone:function(t,i,r,n){var o=n._progress.total,s=n._response;n._progress.loaded<o&&this._onProgress(e.Event("progress",{lengthComputable:!0,loaded:o,total:o}),n),s.result=n.result=t,s.textStatus=n.textStatus=i,s.jqXHR=n.jqXHR=r,this._trigger("done",null,n)},_onFail:function(e,t,i,r){var n=r._response;r.recalculateProgress&&(this._progress.loaded-=r._progress.loaded,this._progress.total-=r._progress.total),n.jqXHR=r.jqXHR=e,n.textStatus=r.textStatus=t,n.errorThrown=r.errorThrown=i,this._trigger("fail",null,r)},_onAlways:function(e,t,i,r){this._trigger("always",null,r)},_onSend:function(t,i){i.submit||this._addConvenienceMethods(t,i);var r,n,o,s,a=this,l=a._getAJAXSettings(i),p=function(){return a._sending+=1,l._bitrateTimer=new a._BitrateTimer,r=r||((n||a._trigger("send",e.Event("send",{delegatedEvent:t}),l)===!1)&&a._getXHRPromise(!1,l.context,n)||a._chunkedUpload(l)||e.ajax(l)).done(function(e,t,i){a._onDone(e,t,i,l)}).fail(function(e,t,i){a._onFail(e,t,i,l)}).always(function(e,t,i){if(a._onAlways(e,t,i,l),a._sending-=1,a._active-=1,l.limitConcurrentUploads&&l.limitConcurrentUploads>a._sending)for(var r=a._slots.shift();r;){if("pending"===a._getDeferredState(r)){r.resolve();break}r=a._slots.shift()}0===a._active&&a._trigger("stop")})};return this._beforeSend(t,l),this.options.sequentialUploads||this.options.limitConcurrentUploads&&this.options.limitConcurrentUploads<=this._sending?(this.options.limitConcurrentUploads>1?(o=e.Deferred(),this._slots.push(o),s=o.pipe(p)):(this._sequence=this._sequence.pipe(p,p),s=this._sequence),s.abort=function(){return n=[void 0,"abort","abort"],r?r.abort():(o&&o.rejectWith(l.context,n),p())},this._enhancePromise(s)):p()},_onAdd:function(t,i){var r,n,o,s,a=this,l=!0,p=e.extend({},this.options,i),u=i.files,d=u.length,h=p.limitMultiFileUploads,c=p.limitMultiFileUploadSize,f=p.limitMultiFileUploadSizeOverhead,g=0,_=this._getParamName(p),m=0;if(!d)return!1;if(c&&void 0===u[0].size&&(c=void 0),(p.singleFileUploads||h||c)&&this._isXHRUpload(p))if(p.singleFileUploads||c||!h)if(!p.singleFileUploads&&c)for(o=[],r=[],s=0;d>s;s+=1)g+=u[s].size+f,(s+1===d||g+u[s+1].size+f>c||h&&s+1-m>=h)&&(o.push(u.slice(m,s+1)),n=_.slice(m,s+1),n.length||(n=_),r.push(n),m=s+1,g=0);else r=_;else for(o=[],r=[],s=0;d>s;s+=h)o.push(u.slice(s,s+h)),n=_.slice(s,s+h),n.length||(n=_),r.push(n);else o=[u],r=[_];return i.originalFiles=u,e.each(o||u,function(n,s){var p=e.extend({},i);return p.files=o?s:[s],p.paramName=r[n],a._initResponseObject(p),a._initProgressObject(p),a._addConvenienceMethods(t,p),l=a._trigger("add",e.Event("add",{delegatedEvent:t}),p)}),l},_replaceFileInput:function(t){var i=t.fileInput,r=i.clone(!0),n=i.is(document.activeElement);t.fileInputClone=r,e("<form></form>").append(r)[0].reset(),i.after(r).detach(),n&&r.focus(),e.cleanData(i.unbind("remove")),this.options.fileInput=this.options.fileInput.map(function(e,t){return t===i[0]?r[0]:t}),i[0]===this.element[0]&&(this.element=r)},_handleFileTreeEntry:function(t,i){var r,n=this,o=e.Deferred(),s=function(e){e&&!e.entry&&(e.entry=t),o.resolve([e])},a=function(e){n._handleFileTreeEntries(e,i+t.name+"/").done(function(e){o.resolve(e)}).fail(s)},l=function(){r.readEntries(function(e){e.length?(p=p.concat(e),l()):a(p)},s)},p=[];return i=i||"",t.isFile?t._file?(t._file.relativePath=i,o.resolve(t._file)):t.file(function(e){e.relativePath=i,o.resolve(e)},s):t.isDirectory?(r=t.createReader(),l()):o.resolve([]),o.promise()},_handleFileTreeEntries:function(t,i){var r=this;return e.when.apply(e,e.map(t,function(e){return r._handleFileTreeEntry(e,i)})).pipe(function(){return Array.prototype.concat.apply([],arguments)})},_getDroppedFiles:function(t){t=t||{};var i=t.items;return i&&i.length&&(i[0].webkitGetAsEntry||i[0].getAsEntry)?this._handleFileTreeEntries(e.map(i,function(e){var t;return e.webkitGetAsEntry?(t=e.webkitGetAsEntry(),t&&(t._file=e.getAsFile()),t):e.getAsEntry()})):e.Deferred().resolve(e.makeArray(t.files)).promise()},_getSingleFileInputFiles:function(t){t=e(t);var i,r,n=t.prop("webkitEntries")||t.prop("entries");if(n&&n.length)return this._handleFileTreeEntries(n);if(i=e.makeArray(t.prop("files")),i.length)void 0===i[0].name&&i[0].fileName&&e.each(i,function(e,t){t.name=t.fileName,t.size=t.fileSize});else{if(r=t.prop("value"),!r)return e.Deferred().resolve([]).promise();i=[{name:r.replace(/^.*\\/,"")}]}return e.Deferred().resolve(i).promise()},_getFileInputFiles:function(t){return t instanceof e&&1!==t.length?e.when.apply(e,e.map(t,this._getSingleFileInputFiles)).pipe(function(){return Array.prototype.concat.apply([],arguments)}):this._getSingleFileInputFiles(t)},_onChange:function(t){var i=this,r={fileInput:e(t.target),form:e(t.target.form)};this._getFileInputFiles(r.fileInput).always(function(n){r.files=n,i.options.replaceFileInput&&i._replaceFileInput(r),i._trigger("change",e.Event("change",{delegatedEvent:t}),r)!==!1&&i._onAdd(t,r)})},_onPaste:function(t){var i=t.originalEvent&&t.originalEvent.clipboardData&&t.originalEvent.clipboardData.items,r={files:[]};i&&i.length&&(e.each(i,function(e,t){var i=t.getAsFile&&t.getAsFile();i&&r.files.push(i)}),this._trigger("paste",e.Event("paste",{delegatedEvent:t}),r)!==!1&&this._onAdd(t,r))},_onDrop:function(t){t.dataTransfer=t.originalEvent&&t.originalEvent.dataTransfer;var i=this,r=t.dataTransfer,n={};r&&r.files&&r.files.length&&(t.preventDefault(),this._getDroppedFiles(r).always(function(r){n.files=r,i._trigger("drop",e.Event("drop",{delegatedEvent:t}),n)!==!1&&i._onAdd(t,n)}))},_onDragOver:t("dragover"),_onDragEnter:t("dragenter"),_onDragLeave:t("dragleave"),_initEventHandlers:function(){this._isXHRUpload(this.options)&&(this._on(this.options.dropZone,{dragover:this._onDragOver,drop:this._onDrop,dragenter:this._onDragEnter,dragleave:this._onDragLeave}),this._on(this.options.pasteZone,{paste:this._onPaste})),e.support.fileInput&&this._on(this.options.fileInput,{change:this._onChange})},_destroyEventHandlers:function(){this._off(this.options.dropZone,"dragenter dragleave dragover drop"),this._off(this.options.pasteZone,"paste"),this._off(this.options.fileInput,"change")},_setOption:function(t,i){var r=-1!==e.inArray(t,this._specialOptions);r&&this._destroyEventHandlers(),this._super(t,i),r&&(this._initSpecialOptions(),this._initEventHandlers())},_initSpecialOptions:function(){var t=this.options;void 0===t.fileInput?t.fileInput=this.element.is('input[type="file"]')?this.element:this.element.find('input[type="file"]'):t.fileInput instanceof e||(t.fileInput=e(t.fileInput)),t.dropZone instanceof e||(t.dropZone=e(t.dropZone)),t.pasteZone instanceof e||(t.pasteZone=e(t.pasteZone))},_getRegExp:function(e){var t=e.split("/"),i=t.pop();return t.shift(),new RegExp(t.join("/"),i)},_isRegExpOption:function(t,i){return"url"!==t&&"string"===e.type(i)&&/^\/.*\/[igm]{0,3}$/.test(i)},_initDataAttributes:function(){var t=this,i=this.options,r=this.element.data();e.each(this.element[0].attributes,function(e,n){var o,s=n.name.toLowerCase();/^data-/.test(s)&&(s=s.slice(5).replace(/-[a-z]/g,function(e){return e.charAt(1).toUpperCase()}),o=r[s],t._isRegExpOption(s,o)&&(o=t._getRegExp(o)),i[s]=o)})},_create:function(){this._initDataAttributes(),this._initSpecialOptions(),this._slots=[],this._sequence=this._getXHRPromise(!0),this._sending=this._active=0,this._initProgressObject(this),this._initEventHandlers()},active:function(){return this._active},progress:function(){return this._progress},add:function(t){var i=this;t&&!this.options.disabled&&(t.fileInput&&!t.files?this._getFileInputFiles(t.fileInput).always(function(e){t.files=e,i._onAdd(null,t)}):(t.files=e.makeArray(t.files),this._onAdd(null,t)))},send:function(t){if(t&&!this.options.disabled){if(t.fileInput&&!t.files){var i,r,n=this,o=e.Deferred(),s=o.promise();return s.abort=function(){return r=!0,i?i.abort():(o.reject(null,"abort","abort"),s)},this._getFileInputFiles(t.fileInput).always(function(e){if(!r){if(!e.length)return void o.reject();t.files=e,i=n._onSend(null,t),i.then(function(e,t,i){o.resolve(e,t,i)},function(e,t,i){o.reject(e,t,i)})}}),this._enhancePromise(s)}if(t.files=e.makeArray(t.files),t.files.length)return this._onSend(null,t)}return this._getXHRPromise(!1,t&&t.context)}})});
					
					function makeLoading(){
						$('.fm_content').html('<p class="text-centered"><i class="fa fa-cog fa-spin fa-lg"></i></p>');
						$('.fm_content').addClass('fm_loading');
					}
					
					function revertLoading(){
						$('.fm_content').removeClass('fm_loading');
					}
					
					function showMessage(message, color){
						if (typeof color == 'undefined'){
							color = 'green';
						}
						$('.fm_message').html(message);
						$('.fm_message').css('background-color', color);
						$('.fm_message').slideDown(100);
						setTimeout(function(){ $('.fm_message').slideUp(100); }, 3000);
					}
					
					function changeDir(directoryNew){
						
						//update global directory
						$('#fm_global_dir').val(directoryNew);
						
						makeLoading();
						
						$.ajax({
							cache: false,
							type: 'POST',
							url: '<?php echo $this->_ajax_file; ?>',
							data: 'func=CHANGE_DIRECTORY&&new_dir='+directoryNew,
							success: function (returned){
								$('.fm_wrapper .fm_content').html(returned);
							},
							error: function(ret){
								$('.fm_wrapper .fm_content').html('<p class="fm_error_message"><?php echo $this->_generic_error_message; ?></p>');
								console.log(ret);
							}, 
							complete: function(){
								
								$.ajax({
									cache: false,
									type: 'POST',
									url: '<?php echo $this->_ajax_file; ?>',
									data: 'func=GET_NEW_BREADCRUMB&&new_dir='+directoryNew,
									success: function (returned){
										$('.fm_wrapper .fm_breadcrumb_additonal').html(returned);
									},
									error: function(){
										$('.fm_wrapper .fm_breadcrumb_additonal').html('<p class="fm_error_message"><?php echo $this->_generic_error_message; ?></p>');
									}, 
									complete: function(){
										revertLoading();
									}
								});
								
							}
						});
						
					}
					
					//change directory
					$('body').on('click', '.fm_change_directory', function(){
						var directoryNew = $(this).attr('data-dir');
						changeDir(directoryNew);
					});
					
					//click file (download)
					$('.fm_wrapper').on('click', '.fm_click_file', function(){
						var form = $(this).attr('data-form');
						$('.fm_wrapper #'+form).submit();
					});
					
					//show element
					$('.fm_wrapper').on('click', '.fm_top_bar_slide_down, .fm_show_elem', function(){
						var item = $(this).attr('data-elem');
						$('.fm_wrapper .'+item).slideDown(100);
						$('.fm_wrapper #'+item).slideDown(100);
						
						if ($(this).hasClass('fm_folder_add_slider')){
							
							//check if root directory to show / hide select
							var directory = $('#fm_global_dir').val();
							if (directory == '<?php echo DOC_ROOT.$this->_root_directory; ?>'){
								$('#fm_new_folder_universal').show();
							} else {
								$('#fm_new_folder_universal').hide();
							}
							
						}
						
					});
					
					//hide element
					$('.fm_wrapper').on('click', '.fm_top_bar_slide_up, .fm_hide_elem', function(){
						var item = $(this).attr('data-elem');
						$('.fm_wrapper .'+item).slideUp(100);
						$('.fm_wrapper #'+item).slideUp(100);
					});
					
					//add new folder
					$('.fm_wrapper').on('click', '.fm_add_folder_submit', function(){
						var item = $(this).attr('data-elem');
						var universal = $('#fm_new_folder_universal').val();
						var folderName = $('#fm_new_folder_name').val();
						var directory = $('#fm_global_dir').val();
						//catch nothing
						if (folderName == ''){
							//do nothing
						} else {
							makeLoading();
							$.ajax({
								cache: false,
								type: 'POST',
								url: '<?php echo $this->_ajax_file; ?>',
								data: 'func=MAKE_NEW_DIR&&make_new_dir='+folderName+'&universal='+universal+'&new_dir='+directory,
								success: function (returned){
									if (returned == 'permission_denied'){
										showMessage('Permission to add a folder to this directory denied', 'red');
									} else {
										showMessage('<i class="fa fa-check fa-fw"></i> Folder Added');
									}
								},
								error: function(returned){
									alert(returned);
									$('.fm_wrapper .fm_content').html('<p class="fm_error_message"><?php echo $this->_generic_error_message; ?></p>');
								}, 
								complete: function(){
									
									$('#fm_new_folder_name').val('');
									$('.fm_wrapper .'+item).slideUp(100);
									
									$.ajax({
										cache: false,
										type: 'POST',
										url: '<?php echo $this->_ajax_file; ?>',
										data: 'func=CHANGE_DIRECTORY&&new_dir='+directory,
										success: function (returned){
											$('.fm_wrapper .fm_content').html(returned);
										},
										error: function(){
											$('.fm_wrapper .fm_content').html('<p class="fm_error_message"><?php echo $this->_generic_error_message; ?></p>');
										}, 
										complete: function(){	
											revertLoading();
										}
									});
									
								}
							});
						}
						
					});
					
					//delete file
					$('.fm_wrapper').on('click', '.fm_delete_file_submit', function(){
						var item = $(this).attr('data-file');
						var directory = $('#fm_global_dir').val();
						
						makeLoading();
						$.ajax({
							cache: false,
							type: 'POST',
							url: '<?php echo $this->_ajax_file; ?>',
							data: 'func=DELETE_FILE&&item='+item+'&new_dir='+directory,
							success: function (returned){
								$('.fm_wrapper .fm_content').html(returned);
							},
							error: function(){
								$('.fm_wrapper .fm_content').html('<p class="fm_error_message"><?php echo $this->_generic_error_message; ?></p>');
							}, 
							complete: function(){
								revertLoading();
								showMessage('<i class="fa fa-check fa-fw"></i> File Deleted');
							}
						});
						
					});
					
					//delete folder
					$('.fm_wrapper').on('click', '.fm_delete_folder_submit', function(){
						var folder = $(this).attr('data-folder');
						var directory = $('#fm_global_dir').val();
						
						makeLoading();
						$.ajax({
							cache: false,
							type: 'POST',
							url: '<?php echo $this->_ajax_file; ?>',
							data: 'func=DELETE_FOLDER&&folder='+folder+'&new_dir='+directory,
							success: function (returned){
								if (returned == 'remove_folder_failed'){
									showMessage('<i class="fa fa-ban fa-fw"></i> Failed to Remove Folder. Is the folder empty?', 'red');
								} else {
									$('.fm_wrapper .fm_content').html(returned);
									showMessage('<i class="fa fa-check fa-fw"></i> Folder Deleted');
								}
							},
							error: function(){
								$('.fm_wrapper .fm_content').html('<p class="fm_error_message"><?php echo $this->_generic_error_message; ?></p>');
							}, 
							complete: function(){
								revertLoading();
							}
						});
						
					});
					
					//upload file
					$('#fm_upload_file').fileupload({
						dataType: 'json',
						add: function (e, data) {
							
							$('.fm_upload_file_form').hide();
							
							var directory = $('#fm_global_dir').val();
							if (directory == '<?php echo DOC_ROOT.$this->_root_directory; ?>'){
								showMessage('<i class="fa fa-ban fa-fw"></i> Cannot Upload Files to Root Directory', 'red');
							} else{
								$('#fm_progress').show();
								data.submit();
							}
							
						},
						progressall: function (e, data) {
							var progress = parseInt(data.loaded / data.total * 100, 10);
							$('#fm_progress .fm_bar').css(
								'width',
								progress + '%'
							);
						},
						done: function (e, data) {
							//$.each(data.result.files, function (index, file) {
								//alert(file.name+' Uploaded');
							//});
							
							$('.fm_upload_file').val();
							$('#fm_progress').fadeOut();
							
							makeLoading();
							
							var directory = $('#fm_global_dir').val();
							
							$.ajax({
								cache: false,
								type: 'POST',
								url: '<?php echo $this->_ajax_file; ?>',
								data: 'func=CHANGE_DIRECTORY&&new_dir='+directory,
								success: function (returned){
									$('.fm_wrapper .fm_content').html(returned);
								},
								error: function(){
									$('.fm_wrapper .fm_content').html('<p class="fm_error_message"><?php echo $this->_generic_error_message; ?></p>');
								}, 
								complete: function(){
									revertLoading();
									showMessage('<i class="fa fa-check fa-fw"></i> File Uploaded');
								}
							});
							
						},
						fail: function (e, data) {
							console.log(e);
							console.log(data);
							$('.fm_upload_file').val();
							$('#fm_progress').fadeOut();
							$.each(data.result.files, function (index, file) {
								alert(file.error);
							});
						}
						
					});

					$('#fm_upload_file').bind('fileuploadsubmit', function (e, data) {
						data.formData = {directory: $('#fm_global_dir').val()};
					});
					
					//preview image
					$('.fm_wrapper').on('click', '.fm_file_preview', function(){
						
						var row = $(this).attr('data-file-row');
						$('.fm_wrapper .file_preview_tr_'+row).slideToggle(100);
						
					});
					
					//select file for tinyMCE
					$('.fm_wrapper').on('click', '.fm_select_file', function(){
						
						window.parent.postMessage({
							sender: 'filemanager',
							url: $(this).attr('data-file'),
							field_id: ''
						});
						
					});
					
					
					
				</script>
			
			<?php
			
		}
	
	/* END JS Function */
	
	/* CSS Function */
	
		public function getCSS(){
			
			?>.fm_wrapper{border:1px solid #ccc;}.fm_wrapper *,.fm_wrapper :after,.fm_wrapper :before{-webkit-box-sizing:border-box!important;-moz-box-sizing:border-box!important;box-sizing:border-box!important}.fm_pointer{cursor:pointer}.fm_message{left:0;top:0;bottom:0;right:0;position:absolute;width:100%;display:none;font-size:.8em;padding:0 15px;height:40px;line-height:40px;color:#fff;background:green}.fm_top_bar{border-bottom:1px solid #ccc}.fm_footer,.fm_top_bar{padding:0 15px;height:40px;line-height:40px;position:relative;}.fm_wrapper{background:#f6f6f6;width:100%;clear:both}.fm_top_bar{width:100%}.fm_top_bar input,.fm_top_bar select{height:30px;padding:5px}.fm_footer{ border-top:1px solid #ccc;font-size:.8em;color:#818181}#fm_progress,.fm_new_folder_form,.fm_upload_file_form{position:absolute;height:40px;line-height:40px;width:100%;display:none;top:0;right:0;left:0;bottom:0;z-index:9999}.fm_loading{text-align:center;background:grey;background:rgba(128,128,128,.8);color:#fff;padding-top:2em;}#fm_progress{background:grey;background:rgba(128,128,128,.8)}.fm_bar{height:40px;background:green}.fm_new_folder_form,.fm_upload_file_form{background:grey;background:rgba(128,128,128,.8);color:#fff}.fm_new_folder_form table td,.fm_upload_file_form table td{padding:4px;vertical-align:middle;border:0;height:40px}.fm_add_folder_submit,.fm_delete_file_submit,.fm_delete_folder_submit{color:green}.fm_hide_elem,.fm_top_bar_slide_up{color:red}.fm_breadcrumb{float:right}.fm_back{height:40px;width:100%;line-height:40px;padding:0 15px;background:gray;color:#fff}.fm_contents_table td,.fm_contents_table th{border-bottom:1px solid #ebebeb;padding:8px 15px;font-size:1em}.fm_error_message,.fm_no_contents{text-align:center}.fm_content{width:100%; border-left:1px solid #ccc;}.fm_contents_table{width:100%;margin:0;background:#fbfbfb}.fm_contents_table th{color:#797979}.fm_contents_table td{text-align:left;height:37px}.fm_contents_table tr td:last-child,.fm_contents_table tr th:last-child{text-align:center}.fm_contents_table td:hover,.fm_contents_table tr:hover{background-color:#e0e0e0!important}.fm_contents_table i.fa-folder{color:#ffd44d}.fm_file_click_form{display:none;margin:0}.fm_actions_cell{position:relative}.fm_delete_file,.fm_delete_folder{display:none;height:100%;height:37px;line-height:37px;text-align:center;background:grey;top:0;right:0;left:0;bottom:0;position:absolute;z-index:9999;color:#fff}<?php
			
		}
	
}
