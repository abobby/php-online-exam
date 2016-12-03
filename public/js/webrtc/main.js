/*
 *  Copyright (c) 2015 The WebRTC project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a BSD-style license
 *  that can be found in the LICENSE file in the root of the source
 *  tree.
 */

'use strict';

// Put variables in global scope to make them available to the browser console.
var video = document.querySelector('video');
var canvas = window.canvas = document.querySelector('canvas');
canvas.width = 480;
canvas.height = 360;

var button = document.querySelector('button');
/*button.onclick = function() {
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  canvas.getContext('2d').
    drawImage(video, 0, 0, canvas.width, canvas.height);
};*/

button.addEventListener('click', function(ev){
    //console.log('hh');
    takepicture();
    ev.preventDefault();
}, false);

var constraints = {
  audio: false,
  video: true
};

function clearphoto() {
    var context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);

    var data = canvas.toDataURL('image/png');
    photo.setAttribute('src', data);
}

function takepicture() {
    //width = 480;
    //height = 360;
    var context = canvas.getContext('2d');
    if (video.videoWidth && video.videoHeight) {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
      var data = canvas.toDataURL('image/png');
      //console.log(data);
      $.ajax({
        type: "POST",
        url: "/stexam/saveStudImgs",
        data: { 
           imgBase64: data,
        }
      }).done(function(o) {
        //console.log('saved'); 
      });
      //photo.setAttribute('src', data);
      
    } else {
      clearphoto();
    }
}

function successCallback(stream) {
  window.stream = stream; // make stream available to browser console
  video.srcObject = stream;
}

function errorCallback(error) {
  console.log('navigator.getUserMedia error: ', error);
}

navigator.getUserMedia(constraints, successCallback, errorCallback);

var fclk = setTimeout(clickthebutton, 20000);
var clk = setInterval(clickthebutton, 120000);

function clickthebutton(){
    $(".takesnap").click();
}

function abortTimer() { // to be called when you want to stop the timer
  clearInterval(clk);
}
