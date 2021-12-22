// Js For Splide Slider
let slideStore = new Map();

var scope = document.querySelectorAll('.tpgb-carousel');
scope.forEach(function(obj){
    splide_init(obj)
});

function splide_init(ele){
    var setting = JSON.parse(ele.getAttribute('data-carousel-option')),
    connId = ele.getAttribute('data-connection'),
    slide = new Splide( ele, setting ).mount();
	slideStore.set( ele, slide);
}