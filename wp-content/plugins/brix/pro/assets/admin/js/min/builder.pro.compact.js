
/*
 *
 * More info at [www.dropzonejs.com](http://www.dropzonejs.com)
 *
 * Copyright (c) 2012, Matias Meno
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

(function() {
  var Dropzone, Emitter, camelize, contentLoaded, detectVerticalSquash, drawImageIOSFix, noop, without,
    __slice = [].slice,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  noop = function() {};

  Emitter = (function() {
    function Emitter() {}

    Emitter.prototype.addEventListener = Emitter.prototype.on;

    Emitter.prototype.on = function(event, fn) {
      this._callbacks = this._callbacks || {};
      if (!this._callbacks[event]) {
        this._callbacks[event] = [];
      }
      this._callbacks[event].push(fn);
      return this;
    };

    Emitter.prototype.emit = function() {
      var args, callback, callbacks, event, _i, _len;
      event = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      this._callbacks = this._callbacks || {};
      callbacks = this._callbacks[event];
      if (callbacks) {
        for (_i = 0, _len = callbacks.length; _i < _len; _i++) {
          callback = callbacks[_i];
          callback.apply(this, args);
        }
      }
      return this;
    };

    Emitter.prototype.removeListener = Emitter.prototype.off;

    Emitter.prototype.removeAllListeners = Emitter.prototype.off;

    Emitter.prototype.removeEventListener = Emitter.prototype.off;

    Emitter.prototype.off = function(event, fn) {
      var callback, callbacks, i, _i, _len;
      if (!this._callbacks || arguments.length === 0) {
        this._callbacks = {};
        return this;
      }
      callbacks = this._callbacks[event];
      if (!callbacks) {
        return this;
      }
      if (arguments.length === 1) {
        delete this._callbacks[event];
        return this;
      }
      for (i = _i = 0, _len = callbacks.length; _i < _len; i = ++_i) {
        callback = callbacks[i];
        if (callback === fn) {
          callbacks.splice(i, 1);
          break;
        }
      }
      return this;
    };

    return Emitter;

  })();

  Dropzone = (function(_super) {
    var extend, resolveOption;

    __extends(Dropzone, _super);

    Dropzone.prototype.Emitter = Emitter;


    /*
    This is a list of all available events you can register on a dropzone object.

    You can register an event handler like this:

        dropzone.on("dragEnter", function() { });
     */

    Dropzone.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "addedfile", "addedfiles", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded", "maxfilesreached", "queuecomplete"];

    Dropzone.prototype.defaultOptions = {
      url: null,
      method: "post",
      withCredentials: false,
      parallelUploads: 2,
      uploadMultiple: false,
      maxFilesize: 256,
      paramName: "file",
      createImageThumbnails: true,
      maxThumbnailFilesize: 10,
      thumbnailWidth: 120,
      thumbnailHeight: 120,
      filesizeBase: 1000,
      maxFiles: null,
      params: {},
      clickable: true,
      ignoreHiddenFiles: true,
      acceptedFiles: null,
      acceptedMimeTypes: null,
      autoProcessQueue: true,
      autoQueue: true,
      addRemoveLinks: false,
      previewsContainer: null,
      hiddenInputContainer: "body",
      capture: null,
      renameFilename: null,
      dictDefaultMessage: "Drop files here to upload",
      dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
      dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
      dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
      dictInvalidFileType: "You can't upload files of this type.",
      dictResponseError: "Server responded with {{statusCode}} code.",
      dictCancelUpload: "Cancel upload",
      dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
      dictRemoveFile: "Remove file",
      dictRemoveFileConfirmation: null,
      dictMaxFilesExceeded: "You can not upload any more files.",
      accept: function(file, done) {
        return done();
      },
      init: function() {
        return noop;
      },
      forceFallback: false,
      fallback: function() {
        var child, messageElement, span, _i, _len, _ref;
        this.element.className = "" + this.element.className + " dz-browser-not-supported";
        _ref = this.element.getElementsByTagName("div");
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          child = _ref[_i];
          if (/(^| )dz-message($| )/.test(child.className)) {
            messageElement = child;
            child.className = "dz-message";
            continue;
          }
        }
        if (!messageElement) {
          messageElement = Dropzone.createElement("<div class=\"dz-message\"><span></span></div>");
          this.element.appendChild(messageElement);
        }
        span = messageElement.getElementsByTagName("span")[0];
        if (span) {
          if (span.textContent != null) {
            span.textContent = this.options.dictFallbackMessage;
          } else if (span.innerText != null) {
            span.innerText = this.options.dictFallbackMessage;
          }
        }
        return this.element.appendChild(this.getFallbackForm());
      },
      resize: function(file) {
        var info, srcRatio, trgRatio;
        info = {
          srcX: 0,
          srcY: 0,
          srcWidth: file.width,
          srcHeight: file.height
        };
        srcRatio = file.width / file.height;
        info.optWidth = this.options.thumbnailWidth;
        info.optHeight = this.options.thumbnailHeight;
        if ((info.optWidth == null) && (info.optHeight == null)) {
          info.optWidth = info.srcWidth;
          info.optHeight = info.srcHeight;
        } else if (info.optWidth == null) {
          info.optWidth = srcRatio * info.optHeight;
        } else if (info.optHeight == null) {
          info.optHeight = (1 / srcRatio) * info.optWidth;
        }
        trgRatio = info.optWidth / info.optHeight;
        if (file.height < info.optHeight || file.width < info.optWidth) {
          info.trgHeight = info.srcHeight;
          info.trgWidth = info.srcWidth;
        } else {
          if (srcRatio > trgRatio) {
            info.srcHeight = file.height;
            info.srcWidth = info.srcHeight * trgRatio;
          } else {
            info.srcWidth = file.width;
            info.srcHeight = info.srcWidth / trgRatio;
          }
        }
        info.srcX = (file.width - info.srcWidth) / 2;
        info.srcY = (file.height - info.srcHeight) / 2;
        return info;
      },

      /*
      Those functions register themselves to the events on init and handle all
      the user interface specific stuff. Overwriting them won't break the upload
      but can break the way it's displayed.
      You can overwrite them if you don't like the default behavior. If you just
      want to add an additional event handler, register it on the dropzone object
      and don't overwrite those options.
       */
      drop: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      dragstart: noop,
      dragend: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      dragenter: function(e) {
        return this.element.classList.add("dz-drag-hover");
      },
      dragover: function(e) {
        return this.element.classList.add("dz-drag-hover");
      },
      dragleave: function(e) {
        return this.element.classList.remove("dz-drag-hover");
      },
      paste: noop,
      reset: function() {
        return this.element.classList.remove("dz-started");
      },
      addedfile: function(file) {
        var node, removeFileEvent, removeLink, _i, _j, _k, _len, _len1, _len2, _ref, _ref1, _ref2, _results;
        if (this.element === this.previewsContainer) {
          this.element.classList.add("dz-started");
        }
        if (this.previewsContainer) {
          file.previewElement = Dropzone.createElement(this.options.previewTemplate.trim());
          file.previewTemplate = file.previewElement;
          this.previewsContainer.appendChild(file.previewElement);
          _ref = file.previewElement.querySelectorAll("[data-dz-name]");
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            node.textContent = this._renameFilename(file.name);
          }
          _ref1 = file.previewElement.querySelectorAll("[data-dz-size]");
          for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
            node = _ref1[_j];
            node.innerHTML = this.filesize(file.size);
          }
          if (this.options.addRemoveLinks) {
            file._removeLink = Dropzone.createElement("<a class=\"dz-remove\" href=\"javascript:undefined;\" data-dz-remove>" + this.options.dictRemoveFile + "</a>");
            file.previewElement.appendChild(file._removeLink);
          }
          removeFileEvent = (function(_this) {
            return function(e) {
              e.preventDefault();
              e.stopPropagation();
              if (file.status === Dropzone.UPLOADING) {
                return Dropzone.confirm(_this.options.dictCancelUploadConfirmation, function() {
                  return _this.removeFile(file);
                });
              } else {
                if (_this.options.dictRemoveFileConfirmation) {
                  return Dropzone.confirm(_this.options.dictRemoveFileConfirmation, function() {
                    return _this.removeFile(file);
                  });
                } else {
                  return _this.removeFile(file);
                }
              }
            };
          })(this);
          _ref2 = file.previewElement.querySelectorAll("[data-dz-remove]");
          _results = [];
          for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
            removeLink = _ref2[_k];
            _results.push(removeLink.addEventListener("click", removeFileEvent));
          }
          return _results;
        }
      },
      removedfile: function(file) {
        var _ref;
        if (file.previewElement) {
          if ((_ref = file.previewElement) != null) {
            _ref.parentNode.removeChild(file.previewElement);
          }
        }
        return this._updateMaxFilesReachedClass();
      },
      thumbnail: function(file, dataUrl) {
        var thumbnailElement, _i, _len, _ref;
        if (file.previewElement) {
          file.previewElement.classList.remove("dz-file-preview");
          _ref = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            thumbnailElement = _ref[_i];
            thumbnailElement.alt = file.name;
            thumbnailElement.src = dataUrl;
          }
          return setTimeout(((function(_this) {
            return function() {
              return file.previewElement.classList.add("dz-image-preview");
            };
          })(this)), 1);
        }
      },
      error: function(file, message) {
        var node, _i, _len, _ref, _results;
        if (file.previewElement) {
          file.previewElement.classList.add("dz-error");
          if (typeof message !== "String" && message.error) {
            message = message.error;
          }
          _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
          _results = [];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
          }
          return _results;
        }
      },
      errormultiple: noop,
      processing: function(file) {
        if (file.previewElement) {
          file.previewElement.classList.add("dz-processing");
          if (file._removeLink) {
            return file._removeLink.textContent = this.options.dictCancelUpload;
          }
        }
      },
      processingmultiple: noop,
      uploadprogress: function(file, progress, bytesSent) {
        var node, _i, _len, _ref, _results;
        if (file.previewElement) {
          _ref = file.previewElement.querySelectorAll("[data-dz-uploadprogress]");
          _results = [];
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            if (node.nodeName === 'PROGRESS') {
              _results.push(node.value = progress);
            } else {
              _results.push(node.style.width = "" + progress + "%");
            }
          }
          return _results;
        }
      },
      totaluploadprogress: noop,
      sending: noop,
      sendingmultiple: noop,
      success: function(file) {
        if (file.previewElement) {
          return file.previewElement.classList.add("dz-success");
        }
      },
      successmultiple: noop,
      canceled: function(file) {
        return this.emit("error", file, "Upload canceled.");
      },
      canceledmultiple: noop,
      complete: function(file) {
        if (file._removeLink) {
          file._removeLink.textContent = this.options.dictRemoveFile;
        }
        if (file.previewElement) {
          return file.previewElement.classList.add("dz-complete");
        }
      },
      completemultiple: noop,
      maxfilesexceeded: noop,
      maxfilesreached: noop,
      queuecomplete: noop,
      addedfiles: noop,
      previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n  <div class=\"dz-image\"><img data-dz-thumbnail /></div>\n  <div class=\"dz-details\">\n    <div class=\"dz-size\"><span data-dz-size></span></div>\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n  <div class=\"dz-success-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Check</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <path d=\"M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" stroke-opacity=\"0.198794158\" stroke=\"#747474\" fill-opacity=\"0.816519475\" fill=\"#FFFFFF\" sketch:type=\"MSShapeGroup\"></path>\n      </g>\n    </svg>\n  </div>\n  <div class=\"dz-error-mark\">\n    <svg width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:sketch=\"http://www.bohemiancoding.com/sketch/ns\">\n      <title>Error</title>\n      <defs></defs>\n      <g id=\"Page-1\" stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\" sketch:type=\"MSPage\">\n        <g id=\"Check-+-Oval-2\" sketch:type=\"MSLayerGroup\" stroke=\"#747474\" stroke-opacity=\"0.198794158\" fill=\"#FFFFFF\" fill-opacity=\"0.816519475\">\n          <path d=\"M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z\" id=\"Oval-2\" sketch:type=\"MSShapeGroup\"></path>\n        </g>\n      </g>\n    </svg>\n  </div>\n</div>"
    };

    extend = function() {
      var key, object, objects, target, val, _i, _len;
      target = arguments[0], objects = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      for (_i = 0, _len = objects.length; _i < _len; _i++) {
        object = objects[_i];
        for (key in object) {
          val = object[key];
          target[key] = val;
        }
      }
      return target;
    };

    function Dropzone(element, options) {
      var elementOptions, fallback, _ref;
      this.element = element;
      this.version = Dropzone.version;
      this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, "");
      this.clickableElements = [];
      this.listeners = [];
      this.files = [];
      if (typeof this.element === "string") {
        this.element = document.querySelector(this.element);
      }
      if (!(this.element && (this.element.nodeType != null))) {
        throw new Error("Invalid dropzone element.");
      }
      if (this.element.dropzone) {
        throw new Error("Dropzone already attached.");
      }
      Dropzone.instances.push(this);
      this.element.dropzone = this;
      elementOptions = (_ref = Dropzone.optionsForElement(this.element)) != null ? _ref : {};
      this.options = extend({}, this.defaultOptions, elementOptions, options != null ? options : {});
      if (this.options.forceFallback || !Dropzone.isBrowserSupported()) {
        return this.options.fallback.call(this);
      }
      if (this.options.url == null) {
        this.options.url = this.element.getAttribute("action");
      }
      if (!this.options.url) {
        throw new Error("No URL provided.");
      }
      if (this.options.acceptedFiles && this.options.acceptedMimeTypes) {
        throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
      }
      if (this.options.acceptedMimeTypes) {
        this.options.acceptedFiles = this.options.acceptedMimeTypes;
        delete this.options.acceptedMimeTypes;
      }
      this.options.method = this.options.method.toUpperCase();
      if ((fallback = this.getExistingFallback()) && fallback.parentNode) {
        fallback.parentNode.removeChild(fallback);
      }
      if (this.options.previewsContainer !== false) {
        if (this.options.previewsContainer) {
          this.previewsContainer = Dropzone.getElement(this.options.previewsContainer, "previewsContainer");
        } else {
          this.previewsContainer = this.element;
        }
      }
      if (this.options.clickable) {
        if (this.options.clickable === true) {
          this.clickableElements = [this.element];
        } else {
          this.clickableElements = Dropzone.getElements(this.options.clickable, "clickable");
        }
      }
      this.init();
    }

    Dropzone.prototype.getAcceptedFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.accepted) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getRejectedFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (!file.accepted) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getFilesWithStatus = function(status) {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status === status) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.getQueuedFiles = function() {
      return this.getFilesWithStatus(Dropzone.QUEUED);
    };

    Dropzone.prototype.getUploadingFiles = function() {
      return this.getFilesWithStatus(Dropzone.UPLOADING);
    };

    Dropzone.prototype.getAddedFiles = function() {
      return this.getFilesWithStatus(Dropzone.ADDED);
    };

    Dropzone.prototype.getActiveFiles = function() {
      var file, _i, _len, _ref, _results;
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status === Dropzone.UPLOADING || file.status === Dropzone.QUEUED) {
          _results.push(file);
        }
      }
      return _results;
    };

    Dropzone.prototype.init = function() {
      var eventName, noPropagation, setupHiddenFileInput, _i, _len, _ref, _ref1;
      if (this.element.tagName === "form") {
        this.element.setAttribute("enctype", "multipart/form-data");
      }
      if (this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message")) {
        this.element.appendChild(Dropzone.createElement("<div class=\"dz-default dz-message\"><span>" + this.options.dictDefaultMessage + "</span></div>"));
      }
      if (this.clickableElements.length) {
        setupHiddenFileInput = (function(_this) {
          return function() {
            if (_this.hiddenFileInput) {
              _this.hiddenFileInput.parentNode.removeChild(_this.hiddenFileInput);
            }
            _this.hiddenFileInput = document.createElement("input");
            _this.hiddenFileInput.setAttribute("type", "file");
            if ((_this.options.maxFiles == null) || _this.options.maxFiles > 1) {
              _this.hiddenFileInput.setAttribute("multiple", "multiple");
            }
            _this.hiddenFileInput.className = "dz-hidden-input";
            if (_this.options.acceptedFiles != null) {
              _this.hiddenFileInput.setAttribute("accept", _this.options.acceptedFiles);
            }
            if (_this.options.capture != null) {
              _this.hiddenFileInput.setAttribute("capture", _this.options.capture);
            }
            _this.hiddenFileInput.style.visibility = "hidden";
            _this.hiddenFileInput.style.position = "absolute";
            _this.hiddenFileInput.style.top = "0";
            _this.hiddenFileInput.style.left = "0";
            _this.hiddenFileInput.style.height = "0";
            _this.hiddenFileInput.style.width = "0";
            document.querySelector(_this.options.hiddenInputContainer).appendChild(_this.hiddenFileInput);
            return _this.hiddenFileInput.addEventListener("change", function() {
              var file, files, _i, _len;
              files = _this.hiddenFileInput.files;
              if (files.length) {
                for (_i = 0, _len = files.length; _i < _len; _i++) {
                  file = files[_i];
                  _this.addFile(file);
                }
              }
              _this.emit("addedfiles", files);
              return setupHiddenFileInput();
            });
          };
        })(this);
        setupHiddenFileInput();
      }
      this.URL = (_ref = window.URL) != null ? _ref : window.webkitURL;
      _ref1 = this.events;
      for (_i = 0, _len = _ref1.length; _i < _len; _i++) {
        eventName = _ref1[_i];
        this.on(eventName, this.options[eventName]);
      }
      this.on("uploadprogress", (function(_this) {
        return function() {
          return _this.updateTotalUploadProgress();
        };
      })(this));
      this.on("removedfile", (function(_this) {
        return function() {
          return _this.updateTotalUploadProgress();
        };
      })(this));
      this.on("canceled", (function(_this) {
        return function(file) {
          return _this.emit("complete", file);
        };
      })(this));
      this.on("complete", (function(_this) {
        return function(file) {
          if (_this.getAddedFiles().length === 0 && _this.getUploadingFiles().length === 0 && _this.getQueuedFiles().length === 0) {
            return setTimeout((function() {
              return _this.emit("queuecomplete");
            }), 0);
          }
        };
      })(this));
      noPropagation = function(e) {
        e.stopPropagation();
        if (e.preventDefault) {
          return e.preventDefault();
        } else {
          return e.returnValue = false;
        }
      };
      this.listeners = [
        {
          element: this.element,
          events: {
            "dragstart": (function(_this) {
              return function(e) {
                return _this.emit("dragstart", e);
              };
            })(this),
            "dragenter": (function(_this) {
              return function(e) {
                noPropagation(e);
                return _this.emit("dragenter", e);
              };
            })(this),
            "dragover": (function(_this) {
              return function(e) {
                var efct;
                try {
                  efct = e.dataTransfer.effectAllowed;
                } catch (_error) {}
                e.dataTransfer.dropEffect = 'move' === efct || 'linkMove' === efct ? 'move' : 'copy';
                noPropagation(e);
                return _this.emit("dragover", e);
              };
            })(this),
            "dragleave": (function(_this) {
              return function(e) {
                return _this.emit("dragleave", e);
              };
            })(this),
            "drop": (function(_this) {
              return function(e) {
                noPropagation(e);
                return _this.drop(e);
              };
            })(this),
            "dragend": (function(_this) {
              return function(e) {
                return _this.emit("dragend", e);
              };
            })(this)
          }
        }
      ];
      this.clickableElements.forEach((function(_this) {
        return function(clickableElement) {
          return _this.listeners.push({
            element: clickableElement,
            events: {
              "click": function(evt) {
                if ((clickableElement !== _this.element) || (evt.target === _this.element || Dropzone.elementInside(evt.target, _this.element.querySelector(".dz-message")))) {
                  _this.hiddenFileInput.click();
                }
                return true;
              }
            }
          });
        };
      })(this));
      this.enable();
      return this.options.init.call(this);
    };

    Dropzone.prototype.destroy = function() {
      var _ref;
      this.disable();
      this.removeAllFiles(true);
      if ((_ref = this.hiddenFileInput) != null ? _ref.parentNode : void 0) {
        this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput);
        this.hiddenFileInput = null;
      }
      delete this.element.dropzone;
      return Dropzone.instances.splice(Dropzone.instances.indexOf(this), 1);
    };

    Dropzone.prototype.updateTotalUploadProgress = function() {
      var activeFiles, file, totalBytes, totalBytesSent, totalUploadProgress, _i, _len, _ref;
      totalBytesSent = 0;
      totalBytes = 0;
      activeFiles = this.getActiveFiles();
      if (activeFiles.length) {
        _ref = this.getActiveFiles();
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          totalBytesSent += file.upload.bytesSent;
          totalBytes += file.upload.total;
        }
        totalUploadProgress = 100 * totalBytesSent / totalBytes;
      } else {
        totalUploadProgress = 100;
      }
      return this.emit("totaluploadprogress", totalUploadProgress, totalBytes, totalBytesSent);
    };

    Dropzone.prototype._getParamName = function(n) {
      if (typeof this.options.paramName === "function") {
        return this.options.paramName(n);
      } else {
        return "" + this.options.paramName + (this.options.uploadMultiple ? "[" + n + "]" : "");
      }
    };

    Dropzone.prototype._renameFilename = function(name) {
      if (typeof this.options.renameFilename !== "function") {
        return name;
      }
      return this.options.renameFilename(name);
    };

    Dropzone.prototype.getFallbackForm = function() {
      var existingFallback, fields, fieldsString, form;
      if (existingFallback = this.getExistingFallback()) {
        return existingFallback;
      }
      fieldsString = "<div class=\"dz-fallback\">";
      if (this.options.dictFallbackText) {
        fieldsString += "<p>" + this.options.dictFallbackText + "</p>";
      }
      fieldsString += "<input type=\"file\" name=\"" + (this._getParamName(0)) + "\" " + (this.options.uploadMultiple ? 'multiple="multiple"' : void 0) + " /><input type=\"submit\" value=\"Upload!\"></div>";
      fields = Dropzone.createElement(fieldsString);
      if (this.element.tagName !== "FORM") {
        form = Dropzone.createElement("<form action=\"" + this.options.url + "\" enctype=\"multipart/form-data\" method=\"" + this.options.method + "\"></form>");
        form.appendChild(fields);
      } else {
        this.element.setAttribute("enctype", "multipart/form-data");
        this.element.setAttribute("method", this.options.method);
      }
      return form != null ? form : fields;
    };

    Dropzone.prototype.getExistingFallback = function() {
      var fallback, getFallback, tagName, _i, _len, _ref;
      getFallback = function(elements) {
        var el, _i, _len;
        for (_i = 0, _len = elements.length; _i < _len; _i++) {
          el = elements[_i];
          if (/(^| )fallback($| )/.test(el.className)) {
            return el;
          }
        }
      };
      _ref = ["div", "form"];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        tagName = _ref[_i];
        if (fallback = getFallback(this.element.getElementsByTagName(tagName))) {
          return fallback;
        }
      }
    };

    Dropzone.prototype.setupEventListeners = function() {
      var elementListeners, event, listener, _i, _len, _ref, _results;
      _ref = this.listeners;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        elementListeners = _ref[_i];
        _results.push((function() {
          var _ref1, _results1;
          _ref1 = elementListeners.events;
          _results1 = [];
          for (event in _ref1) {
            listener = _ref1[event];
            _results1.push(elementListeners.element.addEventListener(event, listener, false));
          }
          return _results1;
        })());
      }
      return _results;
    };

    Dropzone.prototype.removeEventListeners = function() {
      var elementListeners, event, listener, _i, _len, _ref, _results;
      _ref = this.listeners;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        elementListeners = _ref[_i];
        _results.push((function() {
          var _ref1, _results1;
          _ref1 = elementListeners.events;
          _results1 = [];
          for (event in _ref1) {
            listener = _ref1[event];
            _results1.push(elementListeners.element.removeEventListener(event, listener, false));
          }
          return _results1;
        })());
      }
      return _results;
    };

    Dropzone.prototype.disable = function() {
      var file, _i, _len, _ref, _results;
      this.clickableElements.forEach(function(element) {
        return element.classList.remove("dz-clickable");
      });
      this.removeEventListeners();
      _ref = this.files;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        _results.push(this.cancelUpload(file));
      }
      return _results;
    };

    Dropzone.prototype.enable = function() {
      this.clickableElements.forEach(function(element) {
        return element.classList.add("dz-clickable");
      });
      return this.setupEventListeners();
    };

    Dropzone.prototype.filesize = function(size) {
      var cutoff, i, selectedSize, selectedUnit, unit, units, _i, _len;
      selectedSize = 0;
      selectedUnit = "b";
      if (size > 0) {
        units = ['TB', 'GB', 'MB', 'KB', 'b'];
        for (i = _i = 0, _len = units.length; _i < _len; i = ++_i) {
          unit = units[i];
          cutoff = Math.pow(this.options.filesizeBase, 4 - i) / 10;
          if (size >= cutoff) {
            selectedSize = size / Math.pow(this.options.filesizeBase, 4 - i);
            selectedUnit = unit;
            break;
          }
        }
        selectedSize = Math.round(10 * selectedSize) / 10;
      }
      return "<strong>" + selectedSize + "</strong> " + selectedUnit;
    };

    Dropzone.prototype._updateMaxFilesReachedClass = function() {
      if ((this.options.maxFiles != null) && this.getAcceptedFiles().length >= this.options.maxFiles) {
        if (this.getAcceptedFiles().length === this.options.maxFiles) {
          this.emit('maxfilesreached', this.files);
        }
        return this.element.classList.add("dz-max-files-reached");
      } else {
        return this.element.classList.remove("dz-max-files-reached");
      }
    };

    Dropzone.prototype.drop = function(e) {
      var files, items;
      if (!e.dataTransfer) {
        return;
      }
      this.emit("drop", e);
      files = e.dataTransfer.files;
      this.emit("addedfiles", files);
      if (files.length) {
        items = e.dataTransfer.items;
        if (items && items.length && (items[0].webkitGetAsEntry != null)) {
          this._addFilesFromItems(items);
        } else {
          this.handleFiles(files);
        }
      }
    };

    Dropzone.prototype.paste = function(e) {
      var items, _ref;
      if ((e != null ? (_ref = e.clipboardData) != null ? _ref.items : void 0 : void 0) == null) {
        return;
      }
      this.emit("paste", e);
      items = e.clipboardData.items;
      if (items.length) {
        return this._addFilesFromItems(items);
      }
    };

    Dropzone.prototype.handleFiles = function(files) {
      var file, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        _results.push(this.addFile(file));
      }
      return _results;
    };

    Dropzone.prototype._addFilesFromItems = function(items) {
      var entry, item, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = items.length; _i < _len; _i++) {
        item = items[_i];
        if ((item.webkitGetAsEntry != null) && (entry = item.webkitGetAsEntry())) {
          if (entry.isFile) {
            _results.push(this.addFile(item.getAsFile()));
          } else if (entry.isDirectory) {
            _results.push(this._addFilesFromDirectory(entry, entry.name));
          } else {
            _results.push(void 0);
          }
        } else if (item.getAsFile != null) {
          if ((item.kind == null) || item.kind === "file") {
            _results.push(this.addFile(item.getAsFile()));
          } else {
            _results.push(void 0);
          }
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    Dropzone.prototype._addFilesFromDirectory = function(directory, path) {
      var dirReader, errorHandler, readEntries;
      dirReader = directory.createReader();
      errorHandler = function(error) {
        return typeof console !== "undefined" && console !== null ? typeof console.log === "function" ? console.log(error) : void 0 : void 0;
      };
      readEntries = (function(_this) {
        return function() {
          return dirReader.readEntries(function(entries) {
            var entry, _i, _len;
            if (entries.length > 0) {
              for (_i = 0, _len = entries.length; _i < _len; _i++) {
                entry = entries[_i];
                if (entry.isFile) {
                  entry.file(function(file) {
                    if (_this.options.ignoreHiddenFiles && file.name.substring(0, 1) === '.') {
                      return;
                    }
                    file.fullPath = "" + path + "/" + file.name;
                    return _this.addFile(file);
                  });
                } else if (entry.isDirectory) {
                  _this._addFilesFromDirectory(entry, "" + path + "/" + entry.name);
                }
              }
              readEntries();
            }
            return null;
          }, errorHandler);
        };
      })(this);
      return readEntries();
    };

    Dropzone.prototype.accept = function(file, done) {
      if (file.size > this.options.maxFilesize * 1024 * 1024) {
        return done(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(file.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize));
      } else if (!Dropzone.isValidFile(file, this.options.acceptedFiles)) {
        return done(this.options.dictInvalidFileType);
      } else if ((this.options.maxFiles != null) && this.getAcceptedFiles().length >= this.options.maxFiles) {
        done(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles));
        return this.emit("maxfilesexceeded", file);
      } else {
        return this.options.accept.call(this, file, done);
      }
    };

    Dropzone.prototype.addFile = function(file) {
      file.upload = {
        progress: 0,
        total: file.size,
        bytesSent: 0
      };
      this.files.push(file);
      file.status = Dropzone.ADDED;
      this.emit("addedfile", file);
      this._enqueueThumbnail(file);
      return this.accept(file, (function(_this) {
        return function(error) {
          if (error) {
            file.accepted = false;
            _this._errorProcessing([file], error);
          } else {
            file.accepted = true;
            if (_this.options.autoQueue) {
              _this.enqueueFile(file);
            }
          }
          return _this._updateMaxFilesReachedClass();
        };
      })(this));
    };

    Dropzone.prototype.enqueueFiles = function(files) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        this.enqueueFile(file);
      }
      return null;
    };

    Dropzone.prototype.enqueueFile = function(file) {
      if (file.status === Dropzone.ADDED && file.accepted === true) {
        file.status = Dropzone.QUEUED;
        if (this.options.autoProcessQueue) {
          return setTimeout(((function(_this) {
            return function() {
              return _this.processQueue();
            };
          })(this)), 0);
        }
      } else {
        throw new Error("This file can't be queued because it has already been processed or was rejected.");
      }
    };

    Dropzone.prototype._thumbnailQueue = [];

    Dropzone.prototype._processingThumbnail = false;

    Dropzone.prototype._enqueueThumbnail = function(file) {
      if (this.options.createImageThumbnails && file.type.match(/image.*/) && file.size <= this.options.maxThumbnailFilesize * 1024 * 1024) {
        this._thumbnailQueue.push(file);
        return setTimeout(((function(_this) {
          return function() {
            return _this._processThumbnailQueue();
          };
        })(this)), 0);
      }
    };

    Dropzone.prototype._processThumbnailQueue = function() {
      if (this._processingThumbnail || this._thumbnailQueue.length === 0) {
        return;
      }
      this._processingThumbnail = true;
      return this.createThumbnail(this._thumbnailQueue.shift(), (function(_this) {
        return function() {
          _this._processingThumbnail = false;
          return _this._processThumbnailQueue();
        };
      })(this));
    };

    Dropzone.prototype.removeFile = function(file) {
      if (file.status === Dropzone.UPLOADING) {
        this.cancelUpload(file);
      }
      this.files = without(this.files, file);
      this.emit("removedfile", file);
      if (this.files.length === 0) {
        return this.emit("reset");
      }
    };

    Dropzone.prototype.removeAllFiles = function(cancelIfNecessary) {
      var file, _i, _len, _ref;
      if (cancelIfNecessary == null) {
        cancelIfNecessary = false;
      }
      _ref = this.files.slice();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        file = _ref[_i];
        if (file.status !== Dropzone.UPLOADING || cancelIfNecessary) {
          this.removeFile(file);
        }
      }
      return null;
    };

    Dropzone.prototype.createThumbnail = function(file, callback) {
      var fileReader;
      fileReader = new FileReader;
      fileReader.onload = (function(_this) {
        return function() {
          if (file.type === "image/svg+xml") {
            _this.emit("thumbnail", file, fileReader.result);
            if (callback != null) {
              callback();
            }
            return;
          }
          return _this.createThumbnailFromUrl(file, fileReader.result, callback);
        };
      })(this);
      return fileReader.readAsDataURL(file);
    };

    Dropzone.prototype.createThumbnailFromUrl = function(file, imageUrl, callback, crossOrigin) {
      var img;
      img = document.createElement("img");
      if (crossOrigin) {
        img.crossOrigin = crossOrigin;
      }
      img.onload = (function(_this) {
        return function() {
          var canvas, ctx, resizeInfo, thumbnail, _ref, _ref1, _ref2, _ref3;
          file.width = img.width;
          file.height = img.height;
          resizeInfo = _this.options.resize.call(_this, file);
          if (resizeInfo.trgWidth == null) {
            resizeInfo.trgWidth = resizeInfo.optWidth;
          }
          if (resizeInfo.trgHeight == null) {
            resizeInfo.trgHeight = resizeInfo.optHeight;
          }
          canvas = document.createElement("canvas");
          ctx = canvas.getContext("2d");
          canvas.width = resizeInfo.trgWidth;
          canvas.height = resizeInfo.trgHeight;
          drawImageIOSFix(ctx, img, (_ref = resizeInfo.srcX) != null ? _ref : 0, (_ref1 = resizeInfo.srcY) != null ? _ref1 : 0, resizeInfo.srcWidth, resizeInfo.srcHeight, (_ref2 = resizeInfo.trgX) != null ? _ref2 : 0, (_ref3 = resizeInfo.trgY) != null ? _ref3 : 0, resizeInfo.trgWidth, resizeInfo.trgHeight);
          thumbnail = canvas.toDataURL("image/png");
          _this.emit("thumbnail", file, thumbnail);
          if (callback != null) {
            return callback();
          }
        };
      })(this);
      if (callback != null) {
        img.onerror = callback;
      }
      return img.src = imageUrl;
    };

    Dropzone.prototype.processQueue = function() {
      var i, parallelUploads, processingLength, queuedFiles;
      parallelUploads = this.options.parallelUploads;
      processingLength = this.getUploadingFiles().length;
      i = processingLength;
      if (processingLength >= parallelUploads) {
        return;
      }
      queuedFiles = this.getQueuedFiles();
      if (!(queuedFiles.length > 0)) {
        return;
      }
      if (this.options.uploadMultiple) {
        return this.processFiles(queuedFiles.slice(0, parallelUploads - processingLength));
      } else {
        while (i < parallelUploads) {
          if (!queuedFiles.length) {
            return;
          }
          this.processFile(queuedFiles.shift());
          i++;
        }
      }
    };

    Dropzone.prototype.processFile = function(file) {
      return this.processFiles([file]);
    };

    Dropzone.prototype.processFiles = function(files) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.processing = true;
        file.status = Dropzone.UPLOADING;
        this.emit("processing", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("processingmultiple", files);
      }
      return this.uploadFiles(files);
    };

    Dropzone.prototype._getFilesWithXhr = function(xhr) {
      var file, files;
      return files = (function() {
        var _i, _len, _ref, _results;
        _ref = this.files;
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          if (file.xhr === xhr) {
            _results.push(file);
          }
        }
        return _results;
      }).call(this);
    };

    Dropzone.prototype.cancelUpload = function(file) {
      var groupedFile, groupedFiles, _i, _j, _len, _len1, _ref;
      if (file.status === Dropzone.UPLOADING) {
        groupedFiles = this._getFilesWithXhr(file.xhr);
        for (_i = 0, _len = groupedFiles.length; _i < _len; _i++) {
          groupedFile = groupedFiles[_i];
          groupedFile.status = Dropzone.CANCELED;
        }
        file.xhr.abort();
        for (_j = 0, _len1 = groupedFiles.length; _j < _len1; _j++) {
          groupedFile = groupedFiles[_j];
          this.emit("canceled", groupedFile);
        }
        if (this.options.uploadMultiple) {
          this.emit("canceledmultiple", groupedFiles);
        }
      } else if ((_ref = file.status) === Dropzone.ADDED || _ref === Dropzone.QUEUED) {
        file.status = Dropzone.CANCELED;
        this.emit("canceled", file);
        if (this.options.uploadMultiple) {
          this.emit("canceledmultiple", [file]);
        }
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    resolveOption = function() {
      var args, option;
      option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
      if (typeof option === 'function') {
        return option.apply(this, args);
      }
      return option;
    };

    Dropzone.prototype.uploadFile = function(file) {
      return this.uploadFiles([file]);
    };

    Dropzone.prototype.uploadFiles = function(files) {
      var file, formData, handleError, headerName, headerValue, headers, i, input, inputName, inputType, key, method, option, progressObj, response, updateProgress, url, value, xhr, _i, _j, _k, _l, _len, _len1, _len2, _len3, _m, _ref, _ref1, _ref2, _ref3, _ref4, _ref5;
      xhr = new XMLHttpRequest();
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.xhr = xhr;
      }
      method = resolveOption(this.options.method, files);
      url = resolveOption(this.options.url, files);
      xhr.open(method, url, true);
      xhr.withCredentials = !!this.options.withCredentials;
      response = null;
      handleError = (function(_this) {
        return function() {
          var _j, _len1, _results;
          _results = [];
          for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
            file = files[_j];
            _results.push(_this._errorProcessing(files, response || _this.options.dictResponseError.replace("{{statusCode}}", xhr.status), xhr));
          }
          return _results;
        };
      })(this);
      updateProgress = (function(_this) {
        return function(e) {
          var allFilesFinished, progress, _j, _k, _l, _len1, _len2, _len3, _results;
          if (e != null) {
            progress = 100 * e.loaded / e.total;
            for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
              file = files[_j];
              file.upload = {
                progress: progress,
                total: e.total,
                bytesSent: e.loaded
              };
            }
          } else {
            allFilesFinished = true;
            progress = 100;
            for (_k = 0, _len2 = files.length; _k < _len2; _k++) {
              file = files[_k];
              if (!(file.upload.progress === 100 && file.upload.bytesSent === file.upload.total)) {
                allFilesFinished = false;
              }
              file.upload.progress = progress;
              file.upload.bytesSent = file.upload.total;
            }
            if (allFilesFinished) {
              return;
            }
          }
          _results = [];
          for (_l = 0, _len3 = files.length; _l < _len3; _l++) {
            file = files[_l];
            _results.push(_this.emit("uploadprogress", file, progress, file.upload.bytesSent));
          }
          return _results;
        };
      })(this);
      xhr.onload = (function(_this) {
        return function(e) {
          var _ref;
          if (files[0].status === Dropzone.CANCELED) {
            return;
          }
          if (xhr.readyState !== 4) {
            return;
          }
          response = xhr.responseText;
          if (xhr.getResponseHeader("content-type") && ~xhr.getResponseHeader("content-type").indexOf("application/json")) {
            try {
              response = JSON.parse(response);
            } catch (_error) {
              e = _error;
              response = "Invalid JSON response from server.";
            }
          }
          updateProgress();
          if (!((200 <= (_ref = xhr.status) && _ref < 300))) {
            return handleError();
          } else {
            return _this._finished(files, response, e);
          }
        };
      })(this);
      xhr.onerror = (function(_this) {
        return function() {
          if (files[0].status === Dropzone.CANCELED) {
            return;
          }
          return handleError();
        };
      })(this);
      progressObj = (_ref = xhr.upload) != null ? _ref : xhr;
      progressObj.onprogress = updateProgress;
      headers = {
        "Accept": "application/json",
        "Cache-Control": "no-cache",
        "X-Requested-With": "XMLHttpRequest"
      };
      if (this.options.headers) {
        extend(headers, this.options.headers);
      }
      for (headerName in headers) {
        headerValue = headers[headerName];
        if (headerValue) {
          xhr.setRequestHeader(headerName, headerValue);
        }
      }
      formData = new FormData();
      if (this.options.params) {
        _ref1 = this.options.params;
        for (key in _ref1) {
          value = _ref1[key];
          formData.append(key, value);
        }
      }
      for (_j = 0, _len1 = files.length; _j < _len1; _j++) {
        file = files[_j];
        this.emit("sending", file, xhr, formData);
      }
      if (this.options.uploadMultiple) {
        this.emit("sendingmultiple", files, xhr, formData);
      }
      if (this.element.tagName === "FORM") {
        _ref2 = this.element.querySelectorAll("input, textarea, select, button");
        for (_k = 0, _len2 = _ref2.length; _k < _len2; _k++) {
          input = _ref2[_k];
          inputName = input.getAttribute("name");
          inputType = input.getAttribute("type");
          if (input.tagName === "SELECT" && input.hasAttribute("multiple")) {
            _ref3 = input.options;
            for (_l = 0, _len3 = _ref3.length; _l < _len3; _l++) {
              option = _ref3[_l];
              if (option.selected) {
                formData.append(inputName, option.value);
              }
            }
          } else if (!inputType || ((_ref4 = inputType.toLowerCase()) !== "checkbox" && _ref4 !== "radio") || input.checked) {
            formData.append(inputName, input.value);
          }
        }
      }
      for (i = _m = 0, _ref5 = files.length - 1; 0 <= _ref5 ? _m <= _ref5 : _m >= _ref5; i = 0 <= _ref5 ? ++_m : --_m) {
        formData.append(this._getParamName(i), files[i], this._renameFilename(files[i].name));
      }
      return this.submitRequest(xhr, formData, files);
    };

    Dropzone.prototype.submitRequest = function(xhr, formData, files) {
      return xhr.send(formData);
    };

    Dropzone.prototype._finished = function(files, responseText, e) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.status = Dropzone.SUCCESS;
        this.emit("success", file, responseText, e);
        this.emit("complete", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("successmultiple", files, responseText, e);
        this.emit("completemultiple", files);
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    Dropzone.prototype._errorProcessing = function(files, message, xhr) {
      var file, _i, _len;
      for (_i = 0, _len = files.length; _i < _len; _i++) {
        file = files[_i];
        file.status = Dropzone.ERROR;
        this.emit("error", file, message, xhr);
        this.emit("complete", file);
      }
      if (this.options.uploadMultiple) {
        this.emit("errormultiple", files, message, xhr);
        this.emit("completemultiple", files);
      }
      if (this.options.autoProcessQueue) {
        return this.processQueue();
      }
    };

    return Dropzone;

  })(Emitter);

  Dropzone.version = "4.3.0";

  Dropzone.options = {};

  Dropzone.optionsForElement = function(element) {
    if (element.getAttribute("id")) {
      return Dropzone.options[camelize(element.getAttribute("id"))];
    } else {
      return void 0;
    }
  };

  Dropzone.instances = [];

  Dropzone.forElement = function(element) {
    if (typeof element === "string") {
      element = document.querySelector(element);
    }
    if ((element != null ? element.dropzone : void 0) == null) {
      throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
    }
    return element.dropzone;
  };

  Dropzone.autoDiscover = true;

  Dropzone.discover = function() {
    var checkElements, dropzone, dropzones, _i, _len, _results;
    if (document.querySelectorAll) {
      dropzones = document.querySelectorAll(".dropzone");
    } else {
      dropzones = [];
      checkElements = function(elements) {
        var el, _i, _len, _results;
        _results = [];
        for (_i = 0, _len = elements.length; _i < _len; _i++) {
          el = elements[_i];
          if (/(^| )dropzone($| )/.test(el.className)) {
            _results.push(dropzones.push(el));
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      };
      checkElements(document.getElementsByTagName("div"));
      checkElements(document.getElementsByTagName("form"));
    }
    _results = [];
    for (_i = 0, _len = dropzones.length; _i < _len; _i++) {
      dropzone = dropzones[_i];
      if (Dropzone.optionsForElement(dropzone) !== false) {
        _results.push(new Dropzone(dropzone));
      } else {
        _results.push(void 0);
      }
    }
    return _results;
  };

  Dropzone.blacklistedBrowsers = [/opera.*Macintosh.*version\/12/i];

  Dropzone.isBrowserSupported = function() {
    var capableBrowser, regex, _i, _len, _ref;
    capableBrowser = true;
    if (window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) {
      if (!("classList" in document.createElement("a"))) {
        capableBrowser = false;
      } else {
        _ref = Dropzone.blacklistedBrowsers;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          regex = _ref[_i];
          if (regex.test(navigator.userAgent)) {
            capableBrowser = false;
            continue;
          }
        }
      }
    } else {
      capableBrowser = false;
    }
    return capableBrowser;
  };

  without = function(list, rejectedItem) {
    var item, _i, _len, _results;
    _results = [];
    for (_i = 0, _len = list.length; _i < _len; _i++) {
      item = list[_i];
      if (item !== rejectedItem) {
        _results.push(item);
      }
    }
    return _results;
  };

  camelize = function(str) {
    return str.replace(/[\-_](\w)/g, function(match) {
      return match.charAt(1).toUpperCase();
    });
  };

  Dropzone.createElement = function(string) {
    var div;
    div = document.createElement("div");
    div.innerHTML = string;
    return div.childNodes[0];
  };

  Dropzone.elementInside = function(element, container) {
    if (element === container) {
      return true;
    }
    while (element = element.parentNode) {
      if (element === container) {
        return true;
      }
    }
    return false;
  };

  Dropzone.getElement = function(el, name) {
    var element;
    if (typeof el === "string") {
      element = document.querySelector(el);
    } else if (el.nodeType != null) {
      element = el;
    }
    if (element == null) {
      throw new Error("Invalid `" + name + "` option provided. Please provide a CSS selector or a plain HTML element.");
    }
    return element;
  };

  Dropzone.getElements = function(els, name) {
    var e, el, elements, _i, _j, _len, _len1, _ref;
    if (els instanceof Array) {
      elements = [];
      try {
        for (_i = 0, _len = els.length; _i < _len; _i++) {
          el = els[_i];
          elements.push(this.getElement(el, name));
        }
      } catch (_error) {
        e = _error;
        elements = null;
      }
    } else if (typeof els === "string") {
      elements = [];
      _ref = document.querySelectorAll(els);
      for (_j = 0, _len1 = _ref.length; _j < _len1; _j++) {
        el = _ref[_j];
        elements.push(el);
      }
    } else if (els.nodeType != null) {
      elements = [els];
    }
    if (!((elements != null) && elements.length)) {
      throw new Error("Invalid `" + name + "` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");
    }
    return elements;
  };

  Dropzone.confirm = function(question, accepted, rejected) {
    if (window.confirm(question)) {
      return accepted();
    } else if (rejected != null) {
      return rejected();
    }
  };

  Dropzone.isValidFile = function(file, acceptedFiles) {
    var baseMimeType, mimeType, validType, _i, _len;
    if (!acceptedFiles) {
      return true;
    }
    acceptedFiles = acceptedFiles.split(",");
    mimeType = file.type;
    baseMimeType = mimeType.replace(/\/.*$/, "");
    for (_i = 0, _len = acceptedFiles.length; _i < _len; _i++) {
      validType = acceptedFiles[_i];
      validType = validType.trim();
      if (validType.charAt(0) === ".") {
        if (file.name.toLowerCase().indexOf(validType.toLowerCase(), file.name.length - validType.length) !== -1) {
          return true;
        }
      } else if (/\/\*$/.test(validType)) {
        if (baseMimeType === validType.replace(/\/.*$/, "")) {
          return true;
        }
      } else {
        if (mimeType === validType) {
          return true;
        }
      }
    }
    return false;
  };

  if (typeof jQuery !== "undefined" && jQuery !== null) {
    jQuery.fn.dropzone = function(options) {
      return this.each(function() {
        return new Dropzone(this, options);
      });
    };
  }

  if (typeof module !== "undefined" && module !== null) {
    module.exports = Dropzone;
  } else {
    window.Dropzone = Dropzone;
  }

  Dropzone.ADDED = "added";

  Dropzone.QUEUED = "queued";

  Dropzone.ACCEPTED = Dropzone.QUEUED;

  Dropzone.UPLOADING = "uploading";

  Dropzone.PROCESSING = Dropzone.UPLOADING;

  Dropzone.CANCELED = "canceled";

  Dropzone.ERROR = "error";

  Dropzone.SUCCESS = "success";


  /*

  Bugfix for iOS 6 and 7
  Source: http://stackoverflow.com/questions/11929099/html5-canvas-drawimage-ratio-bug-ios
  based on the work of https://github.com/stomita/ios-imagefile-megapixel
   */

  detectVerticalSquash = function(img) {
    var alpha, canvas, ctx, data, ey, ih, iw, py, ratio, sy;
    iw = img.naturalWidth;
    ih = img.naturalHeight;
    canvas = document.createElement("canvas");
    canvas.width = 1;
    canvas.height = ih;
    ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
    data = ctx.getImageData(0, 0, 1, ih).data;
    sy = 0;
    ey = ih;
    py = ih;
    while (py > sy) {
      alpha = data[(py - 1) * 4 + 3];
      if (alpha === 0) {
        ey = py;
      } else {
        sy = py;
      }
      py = (ey + sy) >> 1;
    }
    ratio = py / ih;
    if (ratio === 0) {
      return 1;
    } else {
      return ratio;
    }
  };

  drawImageIOSFix = function(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
    var vertSquashRatio;
    vertSquashRatio = detectVerticalSquash(img);
    return ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
  };


  /*
   * contentloaded.js
   *
   * Author: Diego Perini (diego.perini at gmail.com)
   * Summary: cross-browser wrapper for DOMContentLoaded
   * Updated: 20101020
   * License: MIT
   * Version: 1.2
   *
   * URL:
   * http://javascript.nwbox.com/ContentLoaded/
   * http://javascript.nwbox.com/ContentLoaded/MIT-LICENSE
   */

  contentLoaded = function(win, fn) {
    var add, doc, done, init, poll, pre, rem, root, top;
    done = false;
    top = true;
    doc = win.document;
    root = doc.documentElement;
    add = (doc.addEventListener ? "addEventListener" : "attachEvent");
    rem = (doc.addEventListener ? "removeEventListener" : "detachEvent");
    pre = (doc.addEventListener ? "" : "on");
    init = function(e) {
      if (e.type === "readystatechange" && doc.readyState !== "complete") {
        return;
      }
      (e.type === "load" ? win : doc)[rem](pre + e.type, init, false);
      if (!done && (done = true)) {
        return fn.call(win, e.type || e);
      }
    };
    poll = function() {
      var e;
      try {
        root.doScroll("left");
      } catch (_error) {
        e = _error;
        setTimeout(poll, 50);
        return;
      }
      return init("poll");
    };
    if (doc.readyState !== "complete") {
      if (doc.createEventObject && root.doScroll) {
        try {
          top = !win.frameElement;
        } catch (_error) {}
        if (top) {
          poll();
        }
      }
      doc[add](pre + "DOMContentLoaded", init, false);
      doc[add](pre + "readystatechange", init, false);
      return win[add](pre + "load", init, false);
    }
  };

  Dropzone._autoDiscoverFunction = function() {
    if (Dropzone.autoDiscover) {
      return Dropzone.discover();
    }
  };

  contentLoaded(window, Dropzone._autoDiscoverFunction);

}).call(this);;
( function( $ ) {
	"use strict";

	/**
	 * Refresh the gradient preview.
	 */
	function brix_refresh_gradient( container ) {
		var stops = [],
			direction = $( ".brix-radio-style-color-attributes input:checked", container ).val(),
			reverse = $( ".brix-background-color-gradient-reverse input[type='checkbox']", container )[0].checked;

		$( ".brix-background-color-gradient-table-row", container ).each( function() {
			stops.push( {
				"color": $( ".brix-color-input", this ).val(),
				"position": $( "[type='number']", this ).val()
			} );
		} );

		var c = $( ".brix-background-color-gradient-preview", container )[0],
			size = $( c ).attr( "width" ),
			ctx = c.getContext( "2d" ),
			grd = null;

		ctx.clearRect( 0, 0, c.width, c.height );

		if ( direction === "radial" ) {
			grd = ctx.createRadialGradient( size/2, size/2, 0, size/2, size/2, size/2 );
		}
		else {
			var f1 = 0,
				f2 = 0,
				t1 = 0,
				t2 = 0;

			switch ( direction ) {
				case "diagonal_up":
					f2 = size;
					t1 = size;
					break;
				case "diagonal_down":
					t1 = size;
					t2 = size;
					break;
				case "vertical":
					t2 = size;
					break;
				case "horizontal":
				default:
					t1 = size;
					break;
			}

			grd = ctx.createLinearGradient( f1, f2, t1, t2 );
		}

		var positions = [];

		$.each( stops, function() {
			positions.push( this.position );
		} );

		if ( reverse ) {
			stops.reverse();
		}

		$.each( stops, function( i ) {
			var color = this.color.replace( / /g, "" ) + "";

			try {
				grd.addColorStop( parseInt( positions[ i ], 10 ) / 100, color );
			}
			catch( e ) {}
		} );

		ctx.fillStyle = grd;
		ctx.fillRect( 0, 0, size, size );
	};

	$.brixf.delegate( ".brix-radio-style-color-attributes", "change", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	$.brixf.delegate( ".brix-background-color-gradient-table-row input[type='number']", "input keyup", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	$.brixf.delegate( ".brix-background-color-gradient-table-row .brix-color-input", "change", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	$.brixf.delegate( ".brix-background-color-gradient-reverse input[type='checkbox']", "change", "background", function() {
		var container = $( this ).parents( ".brix-background-color-gradient" ).first();

		brix_refresh_gradient( container );
	} );

	/**
	 * Boot the gradient UI.
	 */
	$.brixf.ui.add( '.brix-background-breakpoint', function() {
		$( ".brix-background-color-gradient", this ).each( function() {
			brix_refresh_gradient( this );
		} );
	} );

	$.brixf.delegate( ".brix-background-breakpoints-select-wrapper select", "change", "background", function() {
		var breakpoint = $( this ).val(),
			field = $( this ).parents( ".brix-field" ).first();

		$( ".brix-background-breakpoint", field ).removeClass( "active" );
		$( ".brix-background-breakpoint[data-breakpoint='" + breakpoint + "']", field ).addClass( "active" );
	} );

	$.brixf.delegate( ".brix-background-inherit-wrapper input[type='checkbox']", "change", "background", function() {
		var wrapper = $( this ).parents( ".brix-background-breakpoint" );

		wrapper.removeClass( "brix-background-inherit" );

		if ( this.checked ) {
			wrapper.addClass( "brix-background-inherit" );
		}
	} );

	$.brixf.delegate( ".brix-background-reponsive-mode-checkbox-wrapper input[name]", "change", "background", function() {
		var field = $( this ).parents( ".brix-field" ).first();

		$( ".brix-background-reponsive-mode-breakpoints-wrapper", field ).removeClass( "active" );

		if ( this.checked ) {
			$( ".brix-background-reponsive-mode-breakpoints-wrapper", field ).addClass( "active" );
		}
		else {
			$( ".brix-background-breakpoints-select-wrapper select" )
				.val( "desktop" )
				.trigger( "change" );
		}
	} );

} )( jQuery );;
( function( $ ) {
	"use strict";

	/**
	 * Remove a breakpoint.
	 */
	$.brixf.delegate( ".brix-breakpoint-remove", "click", "breakpoint", function() {
		var breakpoint = $( this ).parents( ".brix-breakpoint" ).first(),
			id = breakpoint.attr( "data-id" ),
			field = $( this ).parents( ".brix-field" ).first(),
			input = $( "[data-breakpoints-value]", field ),
			breakpoints_value = $.parseJSON( input.val() );

		breakpoint.remove();

		delete breakpoints_value[id];

		if ( brix_breakpoints[id] ) {
			delete brix_breakpoints[id];
		}

		input.val( JSON.stringify( breakpoints_value ) );

		brix_populate_full_width_media_select( field );

		return false;
	} );

	/**
	 * Add a breakpoint.
	 */
	$.brixf.delegate( ".brix-breakpoint-add", "click", "breakpoint", function() {
		var field = $( this ).parents( ".brix-field" ).first(),
			breakpoints_container = $( ".brix-breakpoints-container", field ),
			input = $( "[data-breakpoints-value]", field ),
			breakpoints_value = $.parseJSON( input.val() ),
			full_width_media_select = $( ".brix-full-width-media-query", field );

		if ( window.brix_add_new_breakpoint_modal ) {
			delete window.brix_add_new_breakpoint_modal;
		}

		window.brix_add_new_breakpoint_modal = new BrixBuilderModal(
			"brix_add_new_breakpoint",
			"brix_breakpoint_modal_load",
			{},
			function( data ) {
				if ( data.ev ) {
					delete data.ev;
				}

				var title = brix_breakpoints_i18n_strings.title;

				if ( data.label && data.label != "" ) {
					title = data.label;
				}

				data.custom = true;
				// data.builder = true;

				var id = 'custom_breakpoint_' + ( Date.now() / 1000 | 0 );

				breakpoints_value[id] = data;

				var html = '<div class="brix-breakpoint" data-id="' + id + '">';
						html += '<span class="brix-custom-breakpoint-label">' + title + '</span>';
						html += '<span class="brix-custom-breakpoint-media-query">' + data.media_query + '</span>';

						html += '<button class="brix-breakpoint-remove brix-btn brix-btn-type-action brix-btn-size-small brix-btn-style-text" type="button"><span class="">' + brix_breakpoints_i18n_strings.remove + '</span></button>';
					html += '</div>';

				breakpoints_container.append( html );

				input.val( JSON.stringify( breakpoints_value ) );

				brix_populate_full_width_media_select( field );
			}
		);

		return false;
	} );
} )( jQuery );;
( function( $ ) {
	"use strict";

	var BrixFrontendEditing = function() {

		var self = this;

		// Editing class applied to the body.
		this.editing_class = "brix-is-frontend-editing";
		this.backend_editing_class = "brix-is-backend-editing";

		// Returns a function, that, as long as it continues to be invoked, will not
		// be triggered. The function will be called after it stops being called for
		// N milliseconds. If `immediate` is passed, trigger the function on the
		// leading edge, instead of the trailing.
		this.debounce = function(func, wait, immediate) {
			var timeout;
			return function() {
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
		};

		/**
		 * Blanks the iframe.
		 */
		this.close = function() {
			self.$iframe.attr( "src", "" );

			var wrapper = $( ".brix-frontend-editing-iframe-wrapper" );
			wrapper.removeClass( "brix-device-preview" );
		};

		/**
		 * Populate the iframe.
		 */
		this.open = function( callback ) {
			self.close();

			$( ".brix-frontend-editing-responsive li" ).first().trigger( "click" );

			$( ".brix-frontend-editing-primary-toolbar-wrapper .brix-save-builder-template" ).prop( "disabled", $( ".brix-template-actions .brix-save-builder-template" ).prop( "disabled" )  );
			$( ".brix-frontend-editing-primary-toolbar-wrapper .brix-reset-builder" ).prop( "disabled", $( ".brix-template-actions .brix-reset-builder" ).prop( "disabled" )  );

			$( ".brix-section-row-layout-pre-selector .brix-row-remove" ).trigger( "click" );
			$( ".brix-section-row.brix-editing-row .brix-row-layout-close" ).trigger( "click.brix" );

			self.save_preview( function() {
				if ( callback ) {
					callback();
				}

				self.$iframe.attr( "src", self.$iframe.attr( "data-src" ) );
			} );
		};

		/**
		 * Toggle the frontend editing interface.
		 */
		this.toggle = function() {
			var $body = $( "body" );

			if ( $body.hasClass( self.editing_class ) ) {
				self.clear();
				self.close();

				$body.removeClass( self.editing_class );
				$body.addClass( self.backend_editing_class );
			}
			else {
				$body.removeClass( self.backend_editing_class );
				$body.addClass( self.editing_class );

				self.open( function() {
					setTimeout( function() {
						self.$wrapper.removeClass( "brix-frontend-editing-loading" );
					}, 1000 );
				} );
			}

			return false;
		};

		/**
		 * Get the window element of the iframe.
		 */
		this.getIframeWindow = function( iframe_object ) {
			var doc;

			if (iframe_object.contentWindow) {
				return iframe_object.contentWindow;
			}

			if (iframe_object.window) {
				return iframe_object.window;
			}

			if (!doc && iframe_object.contentDocument) {
				doc = iframe_object.contentDocument;
			}

			if (!doc && iframe_object.document) {
				doc = iframe_object.document;
			}

			if (doc && doc.defaultView) {
				return doc.defaultView;
			}

			if (doc && doc.parentWindow) {
				return doc.parentWindow;
			}

			return undefined;
		};

		/**
		 * Move a builder element.
		 */
		this.move_block = function( start_coords, end_coords ) {
			var block = $( ".brix-block" ).eq( start_coords.index ),
				end_ref_col = $( ".brix-section-column-inner-wrapper" ).eq( end_coords.column ),
				end_ref_block = $( ".brix-block", end_ref_col ).eq( end_coords.index );

			if ( end_ref_block.length ) {
				block.insertBefore( end_ref_block );
			}
			else {
				block.appendTo( end_ref_col );
			}
		}

		/**
		 * Update the builder data without forcing a refresh.
		 */
		this.update = function( redraw ) {
			if ( ! $( "body" ).hasClass( self.editing_class ) ) {
				return;
			}

			if ( typeof redraw === "undefined" ) {
				redraw = false;
			}

			window.brix_controller.refresh( $( ".brix-box" ), redraw );

			window.brix_controller.save_state();
		};

		/**
		 * Refresh the iframe.
		 */
		this.refresh = function() {
			if ( ! $( "body" ).hasClass( self.editing_class ) ) {
				return;
			}

			var force = false;

			if ( typeof window.brix_template_loaded !== "undefined" && window.brix_template_loaded ) {
				force = true;

				delete window.brix_template_loaded;
			}
			else if ( typeof window.brix_block_edit !== "undefined" ) {
				var type = $( window.brix_block_edit ).attr( "data-type" ),
					force_types = [
						"tabs",
						"accordion"
					];

				if ( $.inArray( type, force_types ) !== -1 && $( ".brix-block[data-type='" + type + "']" ).length == 1 ) {
					force = true;
				}

				delete window.brix_block_edit;
			}
			else if ( self.$el && self.$el.length && self.$el.hasClass( "brix-section-column" ) ) {
				if ( $( ".brix-is-carousel" ).length === 1 ) {
					force = true;
				}
			}

			self.clear();

			self.save_preview( function() {
				if ( force ) {
					self.$iframe.attr( "src", "" );
					self.$iframe.attr( "src", self.$iframe.attr( "data-src" ) );

					self.$wrapper.removeClass( "brix-frontend-editing-loading" );
				}
				else {
					$.get(
						self.$iframe.attr( "data-src" ),
						{},
						function( html ) {
							var pattern = /<style id="brix-fw-custom-css-css" type="text\/css">(.*)<\/style>/g,
								style = html.split( pattern );

							if ( style[1] ) {
								$( "#brix-fw-custom-css-css", self.$iframe.contents() ).html( style[1] );
							}

							$( ".brix-builder", self.$iframe.contents() ).replaceWith( $( ".brix-builder", html ) );

							self.getIframeWindow( self.$iframe[0] ).brix_ready();

							self.$wrapper.removeClass( "brix-frontend-editing-loading" );
						}
					);
				}
			} );
		};

		/**
		 * Save a temporary version of the page.
		 */
		this.save_preview = function( callback ) {
			self.$wrapper.addClass( "brix-frontend-editing-loading" );

			$.post(
				ajaxurl,
				{
					"data": $( "#post" ).serializeObject(),
					"post_id": self.id,
					"nonce": self.nonce,
					"action": "brix_frontend_editing_save_preview"
				},
				function( data ) {
			  		callback();
				}
			);
		};

		/**
		 * Save a definitive version of the page.
		 */
		this.save = function() {
			self.$wrapper.addClass( "brix-frontend-editing-loading" );

			$.post(
				ajaxurl,
				{
					"data": $( "#post" ).serializeObject(),
					"post_id": self.id,
					"nonce": self.nonce,
					"action": "brix_frontend_editing_save"
				},
				function( data ) {
					setTimeout( function() {
						self.$wrapper.removeClass( "brix-frontend-editing-loading" );
					}, 500 );
				}
			);
		};

		/**
		 * Clear the selection.
		 */
		this.clear = function() {
			self.change_context( "", 0 );

			var iframe_window = self.getIframeWindow( self.$iframe[0] );

			if ( typeof iframe_window !== "undefined" && typeof iframe_window.brix_frontend_editing !== "undefined" && iframe_window.brix_frontend_editing.clear !== "undefined" ) {
				iframe_window.brix_frontend_editing.clear();
			}
		};

		/**
		 * Open bottom panel.
		 */
		this.open_bottom_panel = function( ctn, action ) {
			ctn = $( ctn );

			$( ".brix-frontend-bottom-panel" ).attr( "data-action", action );
			$( ".brix-frontend-bottom-panel-inner-wrapper" ).html( ctn );
			$( ".brix-frontend-editing-iframe-wrapper" ).addClass( "brix-bottom-panel" );
			$( ".brix-frontend-editing-iframe-wrapper" ).attr( "data-action", action );
		};

		/**
		 * Close bottom panel.
		 */
		this.close_bottom_panel = function( clean_sections ) {
			if ( typeof clean_sections === "undefined" ) {
				clean_sections = true;
			}

			$( ".brix-frontend-editing-iframe-wrapper" ).removeClass( "brix-bottom-panel" );

			if ( clean_sections ) {
				// Remove empty temporary sections
				$( ".brix-section-empty .brix-section-remove" ).trigger( "click" );
			}

			// Remove empty temporary rows
			$( ".brix-row-layout-empty .brix-row-remove" ).trigger( "click" );

			$( ".brix-frontend-bottom-panel" ).attr( "data-action", "" );
			$( ".brix-frontend-bottom-panel-inner-wrapper" ).html( "" );
			$( ".brix-frontend-editing-iframe-wrapper" ).attr( "data-action", "" );
			$( ".brix-section-row.brix-editing-row .brix-row-layout-close" ).first().trigger( "click" );
		};

		/**
		 * Add row.
		 */
		this.add_row = function( count ) {
			self.clear();

			var row = $( ".brix-section-row" ).eq( count ),
				section = brix_parent( row, ".brix-section" );

			window.brix_add_row_index = count;

			$( ".brix-add-new-row", section ).trigger( "click" );

			row = row.next();

			var choices = $( ".brix-section-row-layout-pre-selector .brix-section-row-layout-choices", row )[0].outerHTML;

			self.open_bottom_panel( choices, "add-row" );
		};

		/**
		 * Edit row.
		 */
		this.edit_row = function( count ) {
			var row = $( ".brix-section-row" ).eq( count );

			$( ".brix-edit-row", row ).trigger( "click" );

			var ctn = $( ".brix-section-row-layout-wrapper", row )[0].outerHTML;

			self.open_bottom_panel( ctn, "edit-row" );
		};

		/**
		 * Add section.
		 */
		this.add_section = function( count ) {
			self.clear();

			var section = null;

			if ( typeof count !== "undefined" ) {
				section = $( ".brix-section" ).eq( count );

				$( ".brix-add-new-section-inside", section ).trigger( "click" );

				section = section.next();
			}
			else {
				$( ".brix-start .brix-add-new-section" ).first().trigger( "click" );

				section = $( ".brix-section" ).first();
			}

			var pre_selector = $( ".brix-section-row-layout-pre-selector", section )[0].outerHTML;

			self.open_bottom_panel( pre_selector, "add-section" );
		};

		/**
		 * Perform an action that's linked to a specific control.
		 */
		this.do_action = function() {
			var context = $( ".brix-editing-toolbar-wrapper" ).attr( "data-context" ),
				count = $( ".brix-editing-toolbar-wrapper" ).attr( "data-count" ),
				action = $( this ).attr( "data-action" );

			switch ( context ) {
				case 'section':
					if ( action == 'add' ) {
						self.add_section( count );
					}
					else {
						$( ".brix-section-" + action ).eq( count ).trigger( "click.brix" );
					}

					break;
				case 'row':
					if ( action == 'add' ) {
						self.add_row( count );
					}
					else if ( action == 'edit' ) {
						self.edit_row( count );
					}
					else {
						$( ".brix-" + action + "-row" ).eq( count ).trigger( "click" );
					}

					break;
				case 'column':
					$( ".brix-column-" + action ).eq( count ).trigger( "click" );
					break;
				case 'block':
					$( ".brix-block-" + action ).eq( count ).trigger( "click" );
					break;
				default:
					break;
			}

			if ( action == "remove" ) {
				self.clear();
			}
		};

		/**
		 * Close bottom panel.
		 */
		$( ".brix-close-bottom-panel" ).on( "click", function() {
			self.close_bottom_panel();

			return false;
		} );

		/**
		 * Change the selected element.
		 */
		this.change_element = function() {
			var contexts = [ "section", "row", "column", "block" ],
				toolbar_wrapper = $( ".brix-editing-toolbar-wrapper" ),
				context = toolbar_wrapper.attr( "data-context" ),
				count = toolbar_wrapper.attr( "data-count" ),
				selected_context = -1;

			if ( $( this ).parents( ".brix-editing-toolbar-section-wrapper" ).length ) {
				selected_context = 0;
			}
			else if ( $( this ).parents( ".brix-editing-toolbar-row-wrapper" ).length ) {
				selected_context = 1;
			}
			else if ( $( this ).parents( ".brix-editing-toolbar-column-wrapper" ).length ) {
				selected_context = 2;
			}
			else if ( $( this ).parents( ".brix-editing-toolbar-block-wrapper" ).length ) {
				selected_context = 3;
			}

			if ( selected_context === -1 || selected_context >= context ) {
				return false;
			}

			switch ( selected_context ) {
				case 0:
					self.$el = self.$el.parents( ".brix-section" ).first();
					count = $( self.$el ).index( ".brix-section" );
					break;
				case 1:
					self.$el = self.$el.parents( ".brix-section-row" ).first();
					count = $( self.$el ).index( ".brix-section-row" );
					break;
				case 2:
					self.$el = self.$el.parents( ".brix-section-column" ).first();
					count = $( self.$el ).index( ".brix-section-column" );
					break;
				case 3:
					self.$el = self.$el.parents( ".brix-block" ).first();
					count = $( self.$el ).index( ".brix-block" );
					break;
				default:
					self.$el = null;
					count = 0;
					break;
			}

			self.getIframeWindow( self.$iframe[0] ).brix_frontend_editing.select( contexts[selected_context], count );
		};

		/**
		 * Context change.
		 */
		this.change_context = function( context, count, data ) {
			var toolbar_wrapper = $( ".brix-editing-toolbar-wrapper" );

			toolbar_wrapper.attr( "data-context", context );
			toolbar_wrapper.attr( "data-count", count );
			toolbar_wrapper.removeClass( "brix-section-special" );

			if ( data && data.special ) {
				toolbar_wrapper.addClass( "brix-section-special" );
			}

			$( ".brix-editing-toolbar-block-wrapper i" ).html( "" );

			switch ( context ) {
				case "section":
					self.$el = $( ".brix-section" ).eq( count );
					break;
				case "row":
					self.$el = $( ".brix-section-row" ).eq( count );
					break;
				case "column":
					self.$el = $( ".brix-section-column" ).eq( count );
					break;
				case "block":
					self.$el = $( ".brix-block" ).eq( count );

					$( ".brix-editing-toolbar-block-wrapper i" ).html( "(" + $( ".brix-block-type-label", self.$el ).html() + ")" );
					break;
				default:
					self.$el = null;
					break;
			}
		};

		/**
		 * Add a new block.
		 */
		this.add_block = function( count, index ) {
			if ( index === -1 ) {
				index = null;
			}

			window.brix_add_block_index = index;

			$( ".brix-add-block" ).eq( count ).trigger( "click" );

			return false;
		}

		/**
		 * Activate the button to enable live editing.
		 */
		this.activate = function() {
			if ( ! $( "body" ).hasClass( "post-new-php" ) ) {
				$( "button.brix-frontend-editing" ).prop( "disabled", false );
			}
		};

		/**
		 * Event binding.
		 */
		this.bind = function() {
			$( window ).on( "load", self.activate );

			// Toggle frontend editing.
			$( document ).off( "click.brix", ".brix-frontend-editing" );
			$( document ).on( "click", ".brix-frontend-editing", self.toggle );

			// Close frontend editing.
			self.$close.on( "click", self.toggle );

			// For every update to the Brix data, refresh the iframe.
			$( document ).on( "brix_updated", self.brix_data_selector, self.debounce( function() {
				self.refresh();
			}, 100 ) );

			// Clear the selection.
			self.$clear.on( "click", self.clear );

			// Editing toolbar buttons.
			$( ".brix-editing-btn" ).on( "click", self.do_action );

			// Change the selected element.
			$( ".brix-editing-toolbar-trigger-wrapper" ).on( "click", self.change_element );

			// Responsive breakpoint change.
			$( ".brix-frontend-editing-responsive li" ).on( "click", function() {
				var media = $( "[data-media]", this ).html(),
					pattern = /max-width:\s?(\d+px)/g,
					matches = pattern.exec( media ),
					icn = $( "[data-icn]", this ).html(),
					height = $( this ).attr( 'data-height' ),
					button = $( ".brix-frontend-editing-responsive > i" );

				$( ".brix-frontend-editing-responsive li" ).removeClass( "brix-active" );
				$( this ).addClass( "brix-active" );

				button.html( icn );

				if ( matches && matches[1] ) {
					self.$iframe_wrapper.css( "width", matches[1] );
				}
				else {
					self.$iframe_wrapper.css( "width", "" );
				}

				var wrapper = $( ".brix-frontend-editing-iframe-wrapper" );

				if ( height ) {
					wrapper.addClass( "brix-device-preview" );
					self.$iframe_wrapper.css( "height", height );
				} else {
					wrapper.removeClass( "brix-device-preview" );
					self.$iframe_wrapper.css( "height", "" );
				}

				return false;
			} );

			// Builder reset.
			$( ".brix-frontend-editing-reset" ).on( "click", function() {
				$( ".brix-reset-builder" ).trigger( "click" );

				return false;
			} );

			// Load a master template for the page.
			$( ".brix-frontend-editing-load-template" ).on( "click", function() {
				$( "#brix-templates-manager" ).trigger( "click.brix" );

				return false;
			} );

			// Save the current page as a template.
			$( ".brix-frontend-editing-save-template" ).on( "click", function() {
				$( ".brix-save-builder-template" ).trigger( "click" );

				return false;
			} );

			// Get help button.
			$( ".brix-frontend-editing-help" ).on( "click", function() {
				$( ".brix-frontend-editing-iframe-wrapper" ).addClass( "brix-panel-open" );

				return false;
			} );

			// Close a panel.
			$( ".brix-frontend-editing-panel-close" ).on( "click", function() {
				if ( $( ".brix-frontend-editing-iframe-wrapper" ).hasClass( "brix-panel-open" ) ) {
					$( ".brix-frontend-editing-iframe-wrapper" ).removeClass( "brix-panel-open" );
				}

				return false;
			} );

			// Preview the entire page.
			$( ".brix-frontend-editing-preview" ).on( "click", function() {
				self.clear();

				var wrapper = $( ".brix-frontend-editing-iframe-wrapper" ),
					iframe_body = $( "body", self.$iframe.contents() );

				wrapper.toggleClass( "preview-mode" );

				if ( wrapper.hasClass( "preview-mode" ) ) {
					iframe_body.addClass( "brix-frontend-preview-mode" );
				}
				else {
					iframe_body.removeClass( "brix-frontend-preview-mode" );
				}

				return false;
			} );

			// Add a new row.
			$( document ).on( "click", ".brix-frontend-bottom-panel [data-layout]", function() {
				var layout = $( this ).attr( "data-layout" ),
					panel_action = $( this ).parents( ".brix-frontend-bottom-panel" ).first().attr( "data-action" );

				if ( panel_action == 'edit-row' ) {
					$( ".brix-section-row.brix-editing-row .brix-section-row-layout-choices [data-layout='" + layout + "']" ).trigger( "click" );
				}
				else {
					$( ".brix-box .brix-section-row-layout-pre-selector [data-layout='" + layout + "']" ).first().trigger( "click" );
					self.close_bottom_panel();
				}

				return false;
			} );

			// Use a template.
			$( document ).on( "click", ".brix-frontend-bottom-panel .brix-load-builder-template", function() {
				var is_start = $( ".brix-box.brix-empty" ).length;

				if ( is_start ) {
					$( ".brix-start .brix-load-builder-template" ).trigger( "click" );
				}
				else {
					$( ".brix-box .brix-section-row:not(.brix-editing-row) .brix-section-row-layout-pre-selector .brix-load-builder-template" ).first().trigger( "click" );
				}

				self.close_bottom_panel( false );

				return false;
			} );

			/**
			 * Make sure to remove empty sections on template loading.
			 */
			$( window ).on( "brix_template_loaded", function() {
				// Template loaded marker
				window.brix_template_loaded = true;

				// Remove empty temporary sections
				$( ".brix-section-empty .brix-section-remove" ).trigger( "click" );
			} );

			// Edit row, "Back to layout" panel
			self.link_editing_row_panel_control( ".brix-section-row-back-to-layout", ".brix-section-row-layout-change-wrapper" );

			// Edit row, "Vertical alignment" panel
			self.link_editing_row_panel_control( ".brix-section-row-edit-vertical-alignment", ".brix-section-row-layout-vertical-alignment-wrapper" );

			// Edit row, "Responsive" panel
			self.link_editing_row_panel_control( ".brix-section-row-edit-responsive", ".brix-section-row-layout-responsive-wrapper" );

			/**
			 * Vertical alignment variant.
			 */
			$( document ).on( "mousedown.brix", ".brix-frontend-bottom-panel .brix-vertical-alignment-variant", function() {
				var count = $( this ).index( ".brix-frontend-bottom-panel .brix-vertical-alignment-variant" );

				$( ".brix-section-row.brix-editing-row .brix-vertical-alignment-variant" ).eq( count ).trigger( "mousedown.brix" );

				brix_row.change_vertical_alignment_ui.apply( this );

				return false;
			} );

			/**
			 * Vertical alignment, equal heights column.
			 */
			$( document ).on( "click.brix", ".brix-frontend-bottom-panel .brix-vertical-alignment-equal-heights-label", function() {
				brix_row.change_vertical_alignment_equal_heights_ui.apply( this );

				$( ".brix-section-row.brix-editing-row .brix-vertical-alignment-equal-heights-label" ).first().trigger( "click.brix" );

				return false;
			} );

			/**
			 * Vertical alignment, responsive breakpoint switch.
			 */
			$( document ).on( "change.brix", ".brix-frontend-bottom-panel [data-row-responsive-breakpoint]", function() {
				$( ".brix-section-row.brix-editing-row [data-row-responsive-breakpoint]" ).val( $( this ).val() ).trigger( "change.brix" );

				brix_row.change_responsive_breakpoint_ui.apply( this );

				$( ".brix-section-row.brix-editing-row [data-row-responsive-column]" ).each( function( index ) {
					$( ".brix-frontend-bottom-panel [data-row-responsive-column]" ).eq( index ).val( $( this ).val() ).trigger( "change.brix", false );
				} );

				return false;
			} );

			/**
			 * Vertical alignment, column width switch.
			 */
			$( document ).on( "change.brix", ".brix-frontend-bottom-panel [data-row-responsive-column]", function( e, refresh ) {
				if ( typeof refresh === "undefined" ) {
					refresh = true;
				}

				brix_row.change_responsive_column_ui.apply( this );

				var count = $( this ).index( ".brix-frontend-bottom-panel [data-row-responsive-column]" );

				$( ".brix-section-row.brix-editing-row [data-row-responsive-column]" ).eq( count ).val( $( this ).val() ).trigger( "change.brix", [ refresh ] );

				return false;
			} );

			self.$save_btn.on( "click", function() {
				self.save();

				return false;
			} );
		};

		/**
		 * Bottom panel sub-panels controls.
		 */
		this.link_editing_row_panel_control = function( cls, container ) {
			$( document ).on( "click", ".brix-frontend-bottom-panel " + cls, function() {
				$( ".brix-editing-row.brix-section-row " + cls ).trigger( "click.brix" );

				$( ".brix-frontend-bottom-panel " + container ).html( $( ".brix-editing-row.brix-section-row " + container )[0].innerHTML );

				return false;
			} );
		};

		/**
		 * Initialize the component.
		 */
		this.init = function() {
			self.$el                = null;
			self.$clear             = $( ".brix-editing-clear-btn" );
			self.brix_data_selector = "[data-brix-value]";
			self.$wrapper           = $( ".brix-frontend-editing-iframe-wrapper" );
			self.$iframe_wrapper    = $( ".brix-frontend-editing-iframe", self.$wrapper );
			self.$iframe            = $( "iframe", self.$wrapper );
			self.$close             = $( ".brix-frontend-editing-close" );
			self.nonce              = self.$iframe.attr( "data-nonce" );
			self.id                 = self.$iframe.attr( "data-id" );
			self.$save_btn			= $( ".brix-frontend-editing-save" );

			this.bind();
		};

		this.init();

	};

	window.brix_frontend_editing = new BrixFrontendEditing();

} )( jQuery );;
( function( $ ) {
	"use strict";

	var BrixMedia = function() {

		var self = this;

		/**
		 * Drag starting position.
		 *
		 * @type {Number}
		 */
		this.start_index = 0;

		/**
		 * Drag ending position.
		 *
		 * @type {Number}
		 */
		this.end_index = 0;

		/**
		 * Get the field data object.
		 */
		this.get_data = function( field ) {
			return JSON.parse( $( "input[data-id]", field ).val() );
		};

		/**
		 * Set the field data object.
		 */
		this.set_data = function( field, data ) {
			$( "input[data-id]", field ).val( JSON.stringify( data ) );
		};

		/**
		 * Add an item to the field data object.
		 */
		this.add_item = function( field, item ) {
			var data = self.get_data( field );

			data.push( item );

			self.set_data( field, data );
		};

        /**
		 * Edit an item of the field data object.
		 */
		this.edit_item = function( field, index, item ) {
			var data = self.get_data( field );

            if ( typeof data[index] !== "undefined" ) {
                data[index] = item;
            }

			self.set_data( field, data );
		};

        /**
		 * Remove an item from the field data object.
		 */
        this.remove_item_data = function( field, index ) {
            var data = self.get_data( field );

            if ( typeof data[index] !== "undefined" ) {
                data = data.splice( index, 1 );
            }

            self.set_data( field, data );
        };

		/**
		 * Add a new item from the Media Library.
		 */
		this.add_media = function() {
			var field = $( this ).parents( ".brix-field-brix_media" ).first(),
				container = $( ".brix-media-c", field ),
				template = $( "script[type='text/template'][data-template='brix_media-placeholder']" ),
				thumb_size = "thumbnail",
				data = self.get_data( field ),
				media = new window.Brix_MediaSelector( {
					multiple: true,
					select: function( selection ) {
						$.each( selection, function() {
							var image_url = "",
								selection_data = {};

							if ( this.sizes && this.sizes.full ) {
								image_url = this.sizes.full.url;
							}

							if ( this.sizes && this.sizes[thumb_size] ) {
								image_url = this.sizes[thumb_size].url;
							}

							selection_data = {
								"url": image_url,
								"source": "media"
							};

							container.append( $.brixf.template( template, selection_data ) );
							self.add_item( field, { "gallery_item_id": this.id, "source": "media" } );
						} );
					}
				} );

			media.open( [] );

			return false;
		};

		/**
		 * Add a new item from an external embed source.
		 */
		this.add_embed = function() {
			var ctrl = $( this ),
				field = $( this ).parents( ".brix-field-brix_media" ).first(),
				container = $( ".brix-media-c", field ),
				template = $( "script[type='text/template'][data-template='brix_media-embed-placeholder']" ),
				embed_data = {};

			if ( ! ctrl.is( "[data-add-embed]" ) ) {
				var data = self.get_data( field ),
					placeholder = $( this ),
					index = placeholder.index();

				embed_data = data[ index ];
			}

	        var modal = new $.brixf.modal( "brix-media-embed", embed_data, {
	        	simple: true,

				save: function( data, after_save, nonce ) {
					if ( typeof data.ev !== "undefined" ) {
						delete data.ev;
					}

					if ( data.url ) {
						data.source = "embed";

                        if ( typeof embed_data.url !== "undefined" ) {
                            placeholder.replaceWith( $.brixf.template( template, data ) );
                            self.edit_item( field, index, data );
                        }
                        else {
                            container.append( $.brixf.template( template, data ) );
    						self.add_item( field, data );
                        }
					}
				}
			} );

			modal.open( function( content, key, _data ) {
				var modal_data = {
					"action": "brix_media_embed_modal_load",
					"nonce": container.attr( "data-nonce" ),
					"data": _data
				};

				var origin = ".brix-modal-container[data-key='" + key + "']";
				$( origin + " .brix-modal-wrapper" ).addClass( "brix-loading" );

				$.post(
					ajaxurl,
					modal_data,
					function( response ) {
						response = $( response );

						$( origin + " .brix-modal-wrapper" ).removeClass( "brix-loading" );
						content.html( response );

						setTimeout( function() {
							$.brixf.ui.build();
						}, 1 );
					}
				);
			} );

			return false;
		};

		/**
		 * Remove an item from the media data.
		 */
		this.remove_item = function() {
			var field = $( this ).parents( ".brix-field-brix_media" ).first(),
				placeholder = $( this ).parents( ".brix-image-placeholder" ).first(),
				index = placeholder.index();

			var data = self.get_data( field );

			data.splice( index, 1 );

			self.set_data( field, data );
			placeholder.remove();

			return false;
		};

		/**
		 * Initialize the component.
		 */
		this.init = function() {
			var field = $( this ).parents( ".brix-field-brix_media" ).first();

			$( this ).sortable( {
				items: "> *",
				start: function( event, ui ) {
					self.start_index = $( ui.item ).index();
				},
				update: function( event, ui ) {
					var data = self.get_data( field );

					self.end_index = $( ui.item ).index();

					if ( self.start_index != self.end_index ) {
						data.splice( self.end_index, 0, data.splice( self.start_index, 1 )[0] );

						self.set_data( field, data );
					}
				}
			} );
		};

		/**
		 * Bind events.
		 */
		this.bind = function() {
			/* Initialize the sortable container. */
			$.brixf.ui.add( ".brix-field-brix_media .brix-media-c", this.init );

			/* Add items from the Media Library. */
			$.brixf.delegate( ".brix-field-brix_media [data-add-media]", "click", "brix_media", self.add_media );

			/* Add external embeds. */
			$.brixf.delegate( ".brix-field-brix_media [data-add-embed]", "click", "brix_media", self.add_embed );

			/* Edit an external embed. */
			$.brixf.delegate( ".brix-field-brix_media .brix-media-embed-placeholder", "click", "brix_media", self.add_embed );

			/* Remove a media item. */
			$.brixf.delegate( ".brix-field-brix_media .brix-upload-remove", "click", "brix_media", self.remove_item );
		};

		this.bind();

	};

	( new BrixMedia() );
} )( jQuery );;
( function( $ ) {
	"use strict";

	/**
	 * Templates export call.
	 */
	function brix_templates_call_export( url ) {
		document.getElementById( "brix-template-export-frame" ).src = url;

		return false;
	}

	/**
	 * Templates export.
	 */
	$( document ).on( "click", ".brix-templates-export", function() {
		return brix_templates_call_export( $( this ).attr( "href" ) );
	} );

	/**
	 * Making sure to empty the drop area.
	 */
	function emptyTemplatesDropArea() {
		if ( $( ".brix-user-template-drop-wrapper" ).hasClass( "brix-import-active" ) ) {
			if ( window.brix_templates_dropzone ) {
				window.brix_templates_dropzone.removeAllFiles();
			}
		}
	}

	/**
	 * Templates import toggle.
	 */
	$( document ).on( "click", ".brix-templates-import", function() {
		$( ".brix-template-nav-item[data-nav='user']" ).trigger( "click" );
		$( ".brix-user-template-drop-wrapper" ).toggleClass( "brix-import-active" );

		$( window ).trigger( "brix-templates-import-toggle" );

		emptyTemplatesDropArea();

		return false;
	} );

	/**
	 * Close the template import drop area.
	 */
	$( document ).on( "click", ".brix-user-template-drop-wrapper-close", function() {
		$( ".brix-template-nav-item[data-nav='user']" ).trigger( "click" );
		$( ".brix-user-template-drop-wrapper" ).removeClass( "brix-import-active" );

		return false;
	} );

	/**
	 * Loading of the templates modal.
	 */
	$( window ).on( "brix-templates-modal-loaded", function() {
		window.brix_templates_dropzone = new Dropzone( "#brix-templates-dropzone" );

		window.brix_templates_dropzone.on( "dragenter", function() {
			window.brix_templates_dropzone.removeAllFiles();
		} );
	} );

	/**
	 * Templates upload.
	 */
	var BrixTemplateUpload = function() {

		var self = this;

		// this.sending = function() {
		// };

		// this.error = function() {
		// };

		this.success = function( response ) {
			response = $.parseJSON( response );

			$.get(
				ajaxurl,
				{
					"action": "brix_display_user_templates_list_content_ajax"
				},
				function( markup ) {
					var notice = $( "#brix-drop-wrapper-notice" ),
						container = $( ".brix-templates-wrapper[data-nav='user']" );

					container.addClass( "brix-uploading" );

					$( ".brix-user-template-content" ).html( markup );

					notice.attr( "data-type", response.type );
					notice.html( response.message );

					notice.addClass( "brix-active" );

					setTimeout( function() {
						notice.removeClass( "brix-active" );
					}, 4000 );

					$( "body" ).removeClass( "brix-has-templates" );
					container.addClass( "brix-user-template-empty" );

					if ( $( ".brix-user-template-content .brix-template" ).length ) {
						$( "body" ).addClass( "brix-has-templates" );
						container.removeClass( "brix-user-template-empty" );
					}

					container.removeClass( "brix-uploading" );
				}
			);
		};

		Dropzone.options.brixTemplatesDropzone = {
			uploadMultiple: true,
			acceptedFiles: "text/plain",
			// sending: function() {
			// 	self.sending();
			// },
			// error: function() {
			// 	self.error();
			// },
			success: function( file, response ) {
				self.success( response.trim() );
			},
			init: function() {
				this.on( "drop", function( file ) {
					this.removeAllFiles();
				} );
			}
		};
	};

	var brix_template_upload = new BrixTemplateUpload();

} )( jQuery );