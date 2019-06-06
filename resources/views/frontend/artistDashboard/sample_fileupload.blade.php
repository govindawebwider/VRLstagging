@include('admin.common.header')
<body class="admin video_price">
	<section class="main-page-wrapper">
		<div class="main-content">
			<div id="left-side-wrap"> @include('frontend.artistDashboard.layouts.lsidebar') </div>
			<div class="header-section">
				<div class="top-main-left">
					<a href="{{URL('Dashboard')}}"><span class="logo1 white"><img src="/images/vrl_logo_nav.png" class="img img-responsive"></span> </a>
					<a href="javascript:void(0)" class="toggle menu-toggle"><i class="lnr lnr-menu"></i></a>
				</div>
				<div class="menu-right">
					<div class="user-panel-top">
						<div class="profile_details">
							<div class="profile_img">
								<div class="dropdown user-menu"> <span class="dropdown-toggle"> <span class="admin-img">
											<?php
											$fileName = 'images/Artist/'.$artist->profile_path;
											$imageUrl = \Illuminate\Support\Facades\Storage::disk('s3')->url($fileName);?>
											<img src="{{$imageUrl}}" alt="">
											{{--<img src="{{url('images/Artist/').'/'.$artist->profile_path}}" alt=""> --}}
										</span><span class="admin-name">{{$artist->Name}}</span><i class="arrow"></i> </span>
									<ul>
										@if(session('current_type') == 'Artist')
										<li><a href="{{URL('switch_as_admin')}}"><i class="icon icon-users"></i><span>Switch to Admin</span></a></li>
										@endif
										<li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{ URL($artist->profile_url)}}"> <i class="icon icon-users"></i> <span>view Profile</span> </a> </li>
										<li class="{{ Request::is('ProfileUpdate') ? 'active' : '' }}"> <a href="{{URL('ProfileUpdate')}}"> <i class="icon icon-users"></i> <span>Edit Profile</span> </a> </li>
										<li class="{{ Request::is('change-password') ? 'active' : '' }}"> <a href="{{URL('change-password')}}"> <i class="icon icon-lock"></i> <span>Change Password</span> </a> </li>
										<li class="{{ Request::is('getLogout') ? 'active' : '' }}"> <a href="{{ URL::route('getLogout') }}"> <i class="icon icon-exit"></i> <span>Logout</span> </a> </li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="video_price_wrap">
				<div  class="col-md-12 ">
					<div id="page-wrapper">
						<div class="graphs">
						<div id="breadcrumb"> <a class="tip-bottom" href="/Dashboard" data-original-title="Go to Home"><i class="lnr lnr-home"></i> Home</a><i class="fa fa-angle-right" aria-hidden="true"></i><a class="current" href="/sample_webcame">Upload Sample Video</a> </div>
							@if(Session::has('error'))
							<div class="alert alert-danger"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('error') }}</span> </div>
							@endif
							@if(Session::has('success'))
							<div class="alert alert-success"> <span class="close" data-dismiss="alert">&times;</span> <span class="text-center"> {{Session::get('success') }}</span> </div>
							@endif
							<h1 class="heading">Upload Sample Video</h1>
							
							<style type="text/css">
								.new_form{width:70%;height: auto; display: table;padding: 30px;margin: 0 auto;background: #fff;}
								.new_form label{width: 100%; display: block; margin-bottom: 5px;font-size: 13px; color:#333;}	
								.new_form input[type="text"]{width: 100%; height:35px; border: 1px solid #eee; margin-bottom: 20px; text-align: left; padding-left: 10px; font-size: 13px; color:#333;}	
								.new_form textarea{width: 100%; height:120px; border: 1px solid #eee; margin-bottom: 20px;text-align: left; padding-left: 10px;}	
								.new_form .select_wrp{width: 100%;height: auto;display: table;}
								.new_form .select1{width:48% !important; float: left; height: 35px; margin-bottom: 40px;}
								.new_form .select2{width:48% !important; float: right; height: 35px;margin-bottom: 40px}
								.experiment button{float: left;}							
							</style>
							<section class="experiment recordrtc">
								<div class="new_form">
									<label>Title</label>
									<input type="text" id="name" name="name" >
									<label>Description</label>
									<textarea id="description" name="description"></textarea>
									<div class="select_wrp">

										<div class="select1">
											<label>Profile AutoPlay status</label>
											<select name="profile_autoPlay_status"  id="profile_autoPlay_status">

												<option value="Disable" <?php if(Request::old('profile_autoPlay_status')=='Disable') echo "Selected";?> >Disable</option>

												<option value="Enable" <?php if(Request::old('profile_autoPlay_status')=='Enable') echo "Selected";?> >Enable</option>

											</select>
										</div>

										<div class="select2">
											<label>Download status</label>
											<select name="download_status" id="download_status">

												<option value="Disable"<?php if(Request::old('download_status')=='Disable') echo "Selected";?> >Disable</option>

												<option value="Enable" <?php if(Request::old('download_status')=='Enable') echo "Selected";?> >Enable</option>

											</select>	
										</div>
									</div>	
									<label>Video Record</label>
									<div class="select_wrp">

										<select class="recording-media select1">
											<option value="record-video">Video</option>
											{{-- <option value="record-audio">Audio</option>
											<option value="record-screen">Screen</option> --}}
										</select>


										<select class="media-container-format select2">
											<option >Mp4</option>
											{{-- <option>WebM</option>
											<option >WAV</option>
											<option >Ogg</option>
											<option>Gif</option> --}}
										</select>	
										<span>Video must be 15 Sec.</span>	
									</div>

									<!-- <button class="btn btn-primary center-block">Start Recording</button>	 -->
									<button class="btn btn-primary center-block record_button" id="record_button">Start Recording</button>
									<div style="text-align: center; display: none;">
										<button id="save-to-disk" class="btn btn-primary center-block">Save To Disk</button>
										<button id="open-new-tab" class="btn btn-primary center-block">Open New Tab</button>
										<button id="upload-to-server" class="btn btn-primary center-block">Upload To Server</button>
									</div>
									<div>
										<video controls muted style="height:300px;"></video>
									</div>

								</div>
							</section>

							<script>
								(function() {
									var params = {},
									r = /([^&=]+)=?([^&]*)/g;
									function d(s) {
										return decodeURIComponent(s.replace(/\+/g, ' '));
									}
									var match, search = window.location.search;
									while (match = r.exec(search.substring(1))) {
										params[d(match[1])] = d(match[2]);
										if(d(match[2]) === 'true' || d(match[2]) === 'false') {
											params[d(match[1])] = d(match[2]) === 'true' ? true : false;
										}
									}
									window.params = params;
								})();
							</script>
							<script>
								var recordingDIV = document.querySelector('.recordrtc');
								var recordingMedia = recordingDIV.querySelector('.recording-media');
								var recordingPlayer = recordingDIV.querySelector('video');
								var mediaContainerFormat = recordingDIV.querySelector('.media-container-format');
								recordingDIV.querySelector('button').onclick = function() {
									var button = this;
									if(button.innerHTML === 'Stop Recording') {
										button.disabled = true;
										button.disableStateWaiting = true;
										setTimeout(function() {
											button.disabled = false;
											button.disableStateWaiting = false;
										}, 2 * 1000);
										button.innerHTML = 'Star Recording';
										function stopStream() {
											if(button.stream && button.stream.stop) {
												button.stream.stop();
												button.stream = null;
											}
										}
										if(button.recordRTC) {
											if(button.recordRTC.length) {
												button.recordRTC[0].stopRecording(function(url) {
													if(!button.recordRTC[1]) {
														button.recordingEndedCallback(url);
														stopStream();
														saveToDiskOrOpenNewTab(button.recordRTC[0]);
														return;
													}
													button.recordRTC[1].stopRecording(function(url) {
														button.recordingEndedCallback(url);
														stopStream();
													});
												});
											}
											else {
												button.recordRTC.stopRecording(function(url) {
													button.recordingEndedCallback(url);
													stopStream();
													saveToDiskOrOpenNewTab(button.recordRTC);
												});
											}
										}
										return;
									}
									button.disabled = true;
									var commonConfig = {
										onMediaCaptured: function(stream) {
											button.stream = stream;
											if(button.mediaCapturedCallback) {
												button.mediaCapturedCallback();
											}
											button.innerHTML = 'Stop Recording';
											button.disabled = false;
										},
										onMediaStopped: function() {
											button.innerHTML = 'Start Recording';
											if(!button.disableStateWaiting) {
												button.disabled = false;
											}
										},
										onMediaCapturingFailed: function(error) {
											if(error.name === 'PermissionDeniedError' && !!navigator.mozGetUserMedia) {
												InstallTrigger.install({
													'Foo': {
                                    // https://addons.mozilla.org/firefox/downloads/latest/655146/addon-655146-latest.xpi?src=dp-btn-primary
                                    URL: 'https://addons.mozilla.org/en-US/firefox/addon/enable-screen-capturing/',
                                    toString: function () {
                                    	return this.URL;
                                    }
                                }
                            });
											}
											commonConfig.onMediaStopped();
										}
									};
									if(recordingMedia.value === 'record-video') {
										captureVideo(commonConfig);
										button.mediaCapturedCallback = function() {
											button.recordRTC = RecordRTC(button.stream, {
												type: mediaContainerFormat.value === 'Gif' ? 'gif' : 'video',
												disableLogs: params.disableLogs || false,
												canvas: {
													width: params.canvas_width || 320,
													height: params.canvas_height || 240
												},
                            frameInterval: typeof params.frameInterval !== 'undefined' ? parseInt(params.frameInterval) : 20 // minimum time between pushing frames to Whammy (in milliseconds)
                        });
											button.recordingEndedCallback = function(url) {
												recordingPlayer.src = null;
												recordingPlayer.srcObject = null;
												if(mediaContainerFormat.value === 'Gif') {
													recordingPlayer.pause();
													recordingPlayer.poster = url;
													recordingPlayer.onended = function() {
														recordingPlayer.pause();
														recordingPlayer.poster = URL.createObjectURL(button.recordRTC.blob);
													};
													return;
												}
												recordingPlayer.src = url;
												recordingPlayer.play();
												recordingPlayer.onended = function() {
													recordingPlayer.pause();
													recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
												};
											};
											button.recordRTC.startRecording();
										};
									}
									if(recordingMedia.value === 'record-audio') {
										captureAudio(commonConfig);
										button.mediaCapturedCallback = function() {
											button.recordRTC = RecordRTC(button.stream, {
												type: 'audio',
												bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
												sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
												leftChannel: params.leftChannel || false,
												disableLogs: params.disableLogs || false,
												recorderType: webrtcDetectedBrowser === 'edge' ? StereoAudioRecorder : null
											});
											button.recordingEndedCallback = function(url) {
												var audio = new Audio();
												audio.src = url;
												audio.controls = true;
												recordingPlayer.parentNode.appendChild(document.createElement('hr'));
												recordingPlayer.parentNode.appendChild(audio);
												if(audio.paused) audio.play();
												audio.onended = function() {
													audio.pause();
													audio.src = URL.createObjectURL(button.recordRTC.blob);
												};
											};
											button.recordRTC.startRecording();
										};
									}
									if(recordingMedia.value === 'record-audio-plus-video') {
										captureAudioPlusVideo(commonConfig);
										button.mediaCapturedCallback = function() {
                        if(webrtcDetectedBrowser !== 'firefox') { // opera or chrome etc.
                        	button.recordRTC = [];
                        	if(!params.bufferSize) {
                                // it fixes audio issues whilst recording 720p
                                params.bufferSize = 16384;
                            }
                            var audioRecorder = RecordRTC(button.stream, {
                            	type: 'audio',
                            	bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
                            	sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
                            	leftChannel: params.leftChannel || false,
                            	disableLogs: params.disableLogs || false,
                            	recorderType: webrtcDetectedBrowser === 'edge' ? StereoAudioRecorder : null
                            });
                            var videoRecorder = RecordRTC(button.stream, {
                            	type: 'video',
                            	disableLogs: params.disableLogs || false,
                            	canvas: {
                            		width: params.canvas_width || 320,
                            		height: params.canvas_height || 240
                            	},
                                frameInterval: typeof params.frameInterval !== 'undefined' ? parseInt(params.frameInterval) : 20 // minimum time between pushing frames to Whammy (in milliseconds)
                            });
                            // to sync audio/video playbacks in browser!
                            videoRecorder.initRecorder(function() {
                            	audioRecorder.initRecorder(function() {
                            		audioRecorder.startRecording();
                            		videoRecorder.startRecording();
                            	});
                            });
                            button.recordRTC.push(audioRecorder, videoRecorder);
                            button.recordingEndedCallback = function() {
                            	var audio = new Audio();
                            	audio.src = audioRecorder.toURL();
                            	audio.controls = true;
                            	audio.autoplay = true;
                            	audio.onloadedmetadata = function() {
                            		recordingPlayer.src = videoRecorder.toURL();
                            		recordingPlayer.play();
                            	};
                            	recordingPlayer.parentNode.appendChild(document.createElement('hr'));
                            	recordingPlayer.parentNode.appendChild(audio);
                            	if(audio.paused) audio.play();
                            };
                            return;
                        }
                        button.recordRTC = RecordRTC(button.stream, {
                        	type: 'video',
                        	disableLogs: params.disableLogs || false,
                            // we can't pass bitrates or framerates here
                            // Firefox MediaRecorder API lakes these features
                        });
                        button.recordingEndedCallback = function(url) {
                        	recordingPlayer.srcObject = null;
                        	recordingPlayer.muted = false;
                        	recordingPlayer.src = url;
                        	recordingPlayer.play();
                        	recordingPlayer.onended = function() {
                        		recordingPlayer.pause();
                        		recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                        	};
                        };
                        button.recordRTC.startRecording();
                    };
                }
                if(recordingMedia.value === 'record-screen') {
                	captureScreen(commonConfig);
                	button.mediaCapturedCallback = function() {
                		button.recordRTC = RecordRTC(button.stream, {
                			type: mediaContainerFormat.value === 'Gif' ? 'gif' : 'video',
                			disableLogs: params.disableLogs || false,
                			canvas: {
                				width: params.canvas_width || 320,
                				height: params.canvas_height || 240
                			}
                		});
                		button.recordingEndedCallback = function(url) {
                			recordingPlayer.src = null;
                			recordingPlayer.srcObject = null;
                			if(mediaContainerFormat.value === 'Gif') {
                				recordingPlayer.pause();
                				recordingPlayer.poster = url;
                				recordingPlayer.onended = function() {
                					recordingPlayer.pause();
                					recordingPlayer.poster = URL.createObjectURL(button.recordRTC.blob);
                				};
                				return;
                			}
                			recordingPlayer.src = url;
                			recordingPlayer.play();
                		};
                		button.recordRTC.startRecording();
                	};
                }
                if(recordingMedia.value === 'record-audio-plus-screen') {
                	captureAudioPlusScreen(commonConfig);
                	button.mediaCapturedCallback = function() {
                		button.recordRTC = RecordRTC(button.stream, {
                			type: 'video',
                			disableLogs: params.disableLogs || false,
                            // we can't pass bitrates or framerates here
                            // Firefox MediaRecorder API lakes these features
                        });
                		button.recordingEndedCallback = function(url) {
                			recordingPlayer.srcObject = null;
                			recordingPlayer.muted = false;
                			recordingPlayer.src = url;
                			recordingPlayer.play();
                			recordingPlayer.onended = function() {
                				recordingPlayer.pause();
                				recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                			};
                		};
                		button.recordRTC.startRecording();
                	};
                }
            };
            function captureVideo(config) {
            	captureUserMedia({video: true}, function(videoStream) {
            		recordingPlayer.srcObject = videoStream;
            		recordingPlayer.play();
            		config.onMediaCaptured(videoStream);
            		videoStream.onended = function() {
            			config.onMediaStopped();
            		};
            	}, function(error) {
            		config.onMediaCapturingFailed(error);
            	});
            }
            function captureAudio(config) {
            	captureUserMedia({audio: true}, function(audioStream) {
            		recordingPlayer.srcObject = audioStream;
            		recordingPlayer.play();
            		config.onMediaCaptured(audioStream);
            		audioStream.onended = function() {
            			config.onMediaStopped();
            		};
            	}, function(error) {
            		config.onMediaCapturingFailed(error);
            	});
            }
            function captureAudioPlusVideo(config) {
            	captureUserMedia({video: true, audio: true}, function(audioVideoStream) {
            		recordingPlayer.srcObject = audioVideoStream;
            		recordingPlayer.play();
            		config.onMediaCaptured(audioVideoStream);
            		audioVideoStream.onended = function() {
            			config.onMediaStopped();
            		};
            	}, function(error) {
            		config.onMediaCapturingFailed(error);
            	});
            }
            function captureScreen(config) {
            	getScreenId(function(error, sourceId, screenConstraints) {
            		if (error === 'not-installed') {
            			document.write('<h1><a target="_blank" href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk">Please install this chrome extension then reload the page.</a></h1>');
            		}
            		if (error === 'permission-denied') {
            			alert('Screen capturing permission is denied.');
            		}
            		if (error === 'installed-disabled') {
            			alert('Please enable chrome screen capturing extension.');
            		}
            		if(error) {
            			config.onMediaCapturingFailed(error);
            			return;
            		}
            		captureUserMedia(screenConstraints, function(screenStream) {
            			recordingPlayer.srcObject = screenStream;
            			recordingPlayer.play();
            			config.onMediaCaptured(screenStream);
            			screenStream.onended = function() {
            				config.onMediaStopped();
            			};
            		}, function(error) {
            			config.onMediaCapturingFailed(error);
            		});
            	});
            }
            function captureAudioPlusScreen(config) {
            	getScreenId(function(error, sourceId, screenConstraints) {
            		if (error === 'not-installed') {
            			document.write('<h1><a target="_blank" href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk">Please install this chrome extension then reload the page.</a></h1>');
            		}
            		if (error === 'permission-denied') {
            			alert('Screen capturing permission is denied.');
            		}
            		if (error === 'installed-disabled') {
            			alert('Please enable chrome screen capturing extension.');
            		}
            		if(error) {
            			config.onMediaCapturingFailed(error);
            			return;
            		}
            		screenConstraints.audio = true;
            		captureUserMedia(screenConstraints, function(screenStream) {
            			recordingPlayer.srcObject = screenStream;
            			recordingPlayer.play();
            			config.onMediaCaptured(screenStream);
            			screenStream.onended = function() {
            				config.onMediaStopped();
            			};
            		}, function(error) {
            			config.onMediaCapturingFailed(error);
            		});
            	});
            }
            function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
            	navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
            }
            function setMediaContainerFormat(arrayOfOptionsSupported) {
            	var options = Array.prototype.slice.call(
            		mediaContainerFormat.querySelectorAll('option')
            		);
            	var selectedItem;
            	options.forEach(function(option) {
            		option.disabled = true;
            		if(arrayOfOptionsSupported.indexOf(option.value) !== -1) {
            			option.disabled = false;
            			if(!selectedItem) {
            				option.selected = true;
            				selectedItem = option;
            			}
            		}
            	});
            }
            recordingMedia.onchange = function() {
            	if(this.value === 'record-audio') {
            		setMediaContainerFormat(['WAV', 'Ogg']);
            		return;
            	}
            	setMediaContainerFormat(['WebM', 'Mp4', 'Gif']);
            };
            if(webrtcDetectedBrowser === 'edge') {
                // webp isn't supported in Microsoft Edge
                // neither MediaRecorder API
                // so lets disable both video/screen recording options
                console.warn('Neither MediaRecorder API nor webp is supported in Microsoft Edge. You cam merely record audio.');
                recordingMedia.innerHTML = '<option value="record-audio">Audio</option>';
                setMediaContainerFormat(['WAV']);
            }
            if(webrtcDetectedBrowser === 'firefox') {
                // Firefox implemented both MediaRecorder API as well as WebAudio API
                // Their MediaRecorder implementation supports both audio/video recording in single container format
                // Remember, we can't currently pass bit-rates or frame-rates values over MediaRecorder API (their implementation lakes these features)
                recordingMedia.innerHTML = '<option value="record-audio-plus-video">Audio+Video</option>'
                + '<option value="record-audio-plus-screen">Audio+Screen</option>'
                + recordingMedia.innerHTML;
            }
            // disabling this option because currently this demo
            // doesn't supports publishing two blobs.
            // todo: add support of uploading both WAV/WebM to server.
            //if(false && webrtcDetectedBrowser === 'chrome') {
            	if(webrtcDetectedBrowser === 'chrome') {
            		recordingMedia.innerHTML = '<option value="record-audio-plus-video">Audio+Video</option>'
            		+ '<option value="record-audio-plus-screen">Audio+Screen</option>'
            		+ recordingMedia.innerHTML;
            	//console.info('This RecordRTC demo merely tries to playback recorded audio/video sync inside the browser. It still generates two separate files (WAV/WebM).');
            }
            function saveToDiskOrOpenNewTab(recordRTC) {
            	recordingDIV.querySelector('#save-to-disk').parentNode.style.display = 'block';
            	recordingDIV.querySelector('#save-to-disk').onclick = function() {
            		if(!recordRTC) return alert('No recording found.');
            		recordRTC.save();
            	};
            	recordingDIV.querySelector('#open-new-tab').onclick = function() {
            		if(!recordRTC) return alert('No recording found.');
            		window.open(recordRTC.toURL());
            	};
            	recordingDIV.querySelector('#upload-to-server').disabled = false;
            	recordingDIV.querySelector('#upload-to-server').onclick = function() {
            		if(!recordRTC) return alert('No recording found.');
            		this.disabled = true;
            		var button = this;
            		uploadToServer(recordRTC, function(progress, fileURL) {
            			if(progress === 'ended') {
            				button.disabled = false;
            				button.innerHTML = 'Click to download from server';
            				button.onclick = function() {
            					window.open(fileURL);
            				};
            				return;
            			}
            			button.innerHTML = progress;
            		});
            	};
            }
            var listOfFilesUploaded = [];
            function uploadToServer(recordRTC, callback) {
            	var blob = recordRTC instanceof Blob ? recordRTC : recordRTC.blob;
            	var fileType = blob.type.split('/')[0] || 'audio';
            	var fileName = (Math.random() * 1000).toString().replace('.', '');
            	if (fileType === 'audio') {
            		fileName += '.' + (!!navigator.mozGetUserMedia ? 'ogg' : 'wav');
            	} else {
            		fileName += '.mp4';
            	}
            	var title=$('#name').val();
            	var discription = $('#description').val();
            	var profile_autoPlay_status = $('#profile_autoPlay_status').val();
            	var download_status = $('#download_status').val();

            	if(title == ""){
            		
            		alert("Please Enter Title");

            	}else{

	                // create FormData
	                var formData = new FormData();
	                formData.append('_token', '{{ csrf_token() }}');
	                formData.append(fileType + 'filename', fileName);
	                formData.append(fileType + 'blob', blob);
	                formData.append('title', title);
	                formData.append('discription', discription);
	                formData.append('profile_autoPlay_status', profile_autoPlay_status);
	                formData.append('download_status', download_status);
	                callback('Uploading ' + fileType + ' recording to server.');
	                makeXMLHttpRequest('{{URL('sample_webcam_video_upload')}}', formData, function(progress) {
	                	if (progress !== 'upload-ended') {
	                		callback(progress);
	                		return;
	                	}
	                	var initialURL = location.href.replace(location.href.split('/').pop(), '') + 'uploads/';
	                	console.log(initialURL);
	                	callback('ended', initialURL + fileName);
	                    // to make sure we can delete as soon as visitor leaves
	                    listOfFilesUploaded.push(initialURL + fileName);
	                });
	            }    
	        }
	        function makeXMLHttpRequest(url, data, callback) {
	        	var request = new XMLHttpRequest();
	        	request.onreadystatechange = function() {
	        		console.log(request.responseText);
	        		if (request.readyState == 4 && request.status == 200) {
	        			console.log(request.responseText);
	        			alert("File upload successfully");
	        			var base_url = window.location.origin;
	        			window.location = base_url+"/artist_video";
	        			//window.location = "https://www.videorequestline.com/artist_video";
	        			callback('upload-ended');
	        		}
	        	};
	        	request.upload.onloadstart = function() {
	        		callback('Upload started...');
	        	};
	        	request.upload.onprogress = function(event) {
	        		callback('Upload Progress ' + Math.round(event.loaded / event.total * 100) + "% please waiting..... ");
	        	};
	        	request.upload.onload = function() {
	        		callback('progress-about-to-end');
	        	};
	        	request.upload.onload = function() {
	        		callback('progress-ended');
	        	};
	        	request.upload.onerror = function(error) {
	        		callback('Failed to upload to server');
	        		console.error('XMLHttpRequest failed', error);
	        	};
	        	request.upload.onabort = function(error) {
	        		callback('Upload aborted.');
	        		console.error('XMLHttpRequest aborted', error);
	        	};
	        	request.open('POST', url);
	        	request.send(data);
	        }
	        window.onbeforeunload = function() {
	        	recordingDIV.querySelector('button').disabled = false;
	        	recordingMedia.disabled = false;
	        	mediaContainerFormat.disabled = false;
	        	if(!listOfFilesUploaded.length) return;
	        	listOfFilesUploaded.forEach(function(fileURL) {
	        		var request = new XMLHttpRequest();
	        		request.onreadystatechange = function() {
	        			if (request.readyState == 4 && request.status == 200) {
	        				if(this.responseText === ' problem deleting files.') {
	        					alert('Failed to delete ' + fileURL + ' from the server.');
	        					return;
	        				}
	        				listOfFilesUploaded = [];
	        				alert('You can leave now. Your files are removed from the server.');
	        			}
	        		};
	        		request.open('POST', 'delete.php');
	        		var formData = new FormData();
	        		formData.append('delete-file', fileURL.split('/').pop());
	        		request.send(formData);
	        	});
	        	return 'Please wait few seconds before your recordings are deleted from the server.';
	        };
	    </script>
	    <!-- commits.js is useless for you! -->
	    <script>
	    	window.useThisGithubPath = 'muaz-khan/RecordRTC';
	    </script>
	    <script src="https://cdn.webrtc-experiment.com/commits.js" async></script>
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 
	    <script type="text/javascript">
	    	$(document).ready(function(){
	    		$(".upload_video").click(function(){
	    			alert('fileName');
	    		});

	    	});
	    </script> 
	    <!-- End first camera code --> 
	    <!-- second camera code -->
	    <div class="form-group" id="container">
	    	<div class="control-label">
	    		<label for="into">
	    			<video id="gum" autoplay muted></video>
	    			<video id="recorded" autoplay loop></video>
	    		</label>
	    	</div>
     <!--  <div class="control-box">
       <input class="btn btn-primary center-block" id="record" disabled type="Start Recording" value="Start Recording">
       <input class="btn btn-primary center-block" id="play" disabled type="Play" value="Play">
       <input class="btn btn-primary center-block" id="download" disabled type="Download" value="Download">
       <input class="btn btn-primary center-block" id="upload" disabled type="Upload" value="Upload">
   </div> -->
</div>
<!-- <div id="container">
	<video id="gum" autoplay muted></video>
	<video id="recorded" autoplay loop></video>
	<div>
		<button id="record" disabled>Start Recording</button>
		<button id="play" disabled>Play</button>
		<button id="download" disabled>Download</button>
		<button id="upload" disabled>Upload</button>
	</div>
</div> -->
<div >
	<div> </div>
</div>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script> 
<script src="camera_js/main.js"></script> 
<script src="../../../js/lib/ga.js"></script> 
<!--End second camera code  --> 
</div>
</div>
</div>
</div>
</div>
@include('admin.common.footer') </section>
<script type="text/javascript">
	$( document ).ready(function() {
		$( ".dropdown.user-menu" ).click(function() {
			$( '.dropdown.user-menu ul' ).toggle();
		});
		var fewSeconds = 17;
		//var fewSeconds = 2;
		$("#record_button").click(function() {
			var btn = $(this);
			$(this).hide();
			setTimeout(function(){
				btn.show();
			}, fewSeconds*1000);
		});
	});
</script>
</body>
</html>