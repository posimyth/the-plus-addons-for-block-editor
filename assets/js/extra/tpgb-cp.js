(function(){xdLocalStorage.init({iframeUrl:"https://posimyththemes.com/tpcp/",initCallback:function(){}}),window.addEventListener("load",function(){function a(a){let b=a.blockCode.BlockType,c=a.blockCode,d=wp.blocks.parse(c);wp.data.dispatch("core/block-editor").insertBlocks(d)}let b=document.querySelector(".edit-post-header__toolbar"),c=document.createElement("div");c.classList.add("tpgb-paste-clipboard-wrap");c.innerHTML="<button id=\"tpgb-paste-clipboard\" title=\"Paste\"><svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fad\" data-icon=\"paste\" class=\"svg-inline--fa fa-paste fa-w-14\" width=\"20\" fill=\"#6f14f1\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\"><g class=\"fa-group\"><path class=\"fa-secondary\" d=\"M320 264V160H184a24 24 0 0 0-24 24v304a24 24 0 0 0 24 24h240a24 24 0 0 0 24-24V288H344a24.07 24.07 0 0 1-24-24zm121-31l-66-66a24 24 0 0 0-17-7h-6v96h96v-6.06a24 24 0 0 0-7-16.94z\" opacity=\"0.4\"></path><path class=\"fa-primary\" d=\"M296 32h-80.61a63.94 63.94 0 0 0-110.78 0H24A24 24 0 0 0 0 56v336a24 24 0 0 0 24 24h104V184a56.06 56.06 0 0 1 56-56h136V56a24 24 0 0 0-24-24zM160 88a24 24 0 1 1 24-24 24 24 0 0 1-24 24z\"></path></g></svg></button>",b.appendChild(c),document.getElementById("tpgb-paste-clipboard").addEventListener("click",function(){xdLocalStorage.getItem("theplus-c-p-element",function(b){b&&b.value!=null&&b.value&&a(JSON.parse(b.value))})})})})();