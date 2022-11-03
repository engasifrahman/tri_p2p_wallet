import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
 
//call toast to show necessary information
iziToast.settings({
    theme: 'dark', // dark, light
    messageSize: '14',
    close: true,
    overlay: false,
    maxWidth: '400px',
    position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
    progressBar: true,
    progressBarEasing: 'linear',
    transitionIn: 'bounceInTop',
    transitionOut: 'flipOutX',
    transitionInMobile: 'bounceInTop',
    transitionOutMobile: 'flipOutX'
}); 

export let myToast = function(type, msg, title = '', timeout = 3000){
    if(type == 'success'){
        iziToast.success({
            title: title, //'Success',
            message: msg,
            timeout: timeout,
            backgroundColor: '#4CAF50', // blue, red, green, yellow or manual color rgb hex
            progressBarColor: '#FFFFFF',        
        })
    } if(type == 'info'){
        iziToast.info({
            theme: 'dark', // dark, light
            title: title, //'Info',
            message: msg,
            timeout: timeout,
            backgroundColor: '#2196F3', // blue, red, green, yellow or manual color rgb hex
            progressBarColor: '#FFFFFF',
        });
    } else if(type == 'warning' ){
        iziToast.warning({
            title: title, //'Warning',
            message: msg,
            timeout: timeout,
            backgroundColor: '#FFCC00', // blue, red, green, yellow or manual color rgb hex
            progressBarColor: '',
        });
    } else if(type == 'error' ){
        iziToast.error({
            title: title, //'Error',
            message: msg,
            timeout: timeout,
            backgroundColor: '#CF0603', // blue, red, green, yellow or manual color rgb hex
            progressBarColor: '#FFFFFF',

        });
    }
}
    