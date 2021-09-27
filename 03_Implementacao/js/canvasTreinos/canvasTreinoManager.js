let app = null;
let objSelected = null;
let objDrawInCanvas = null

function initCanvasFull(counter){
    let canvas = document.getElementById('canvas');
    let url = 'backgroundCanvas-row_' + counter.toString();
    let div = document.getElementById(url);
    canvas.width = 851;
    canvas.height = 1304;
    div.setAttribute("style", "background-image: url('img/campoInteiro.jpg');background-repeat: no-repeat;background-size: contain;");


    app = new CanvasTreino();
    app.init();
    app.drawObjects(canvas);
    initSquare();
    canvas.addEventListener('mousedown', dragObject, false);
    canvas.addEventListener('dblclick', newObject, false );
    canvas.addEventListener('click', selectObj, false );

}

function initCanvasHalfRight(counter){
    let canvas = document.getElementById('canvas');
    let url = 'backgroundCanvas-row_' + counter.toString();
    let div = document.getElementById(url);
    canvas.width = 851;
    canvas.height = 1304;
    div.setAttribute("style", "background-image: url('img/meioCampoDireita.jpg');background-repeat: no-repeat;background-size: contain;");

    app = new CanvasTreino();
    app.init();
    app.drawObjects(canvas);
    initSquare();
    canvas.addEventListener('mousedown', dragObject, false);
    canvas.addEventListener('dblclick', newObject, false );
    canvas.addEventListener('click', selectObj, false );
}

function initCanvasHalfLeft(counter){
    let canvas = document.getElementById('canvas');
    let url = 'backgroundCanvas-row_' + counter.toString();
    let div = document.getElementById(url);
    canvas.width = 851;
    canvas.height = 1304;
    div.setAttribute("style", "background-image: url('img/meioCampoEsquerda.jpg');background-repeat: no-repeat;background-size: contain;");

    app = new CanvasTreino();
    app.init();
    app.drawObjects(canvas);
    initSquare();
    canvas.addEventListener('mousedown', dragObject, false);
    canvas.addEventListener('dblclick', newObject, false );
    canvas.addEventListener('click', selectObj, false );
}
// Select Object
function selectObj(ev){
    let canvas = document.getElementById('canvas');
    let r = canvas.getBoundingClientRect();
    let mx = null;
    let my = null;

    if ( ev.layerX ||  ev.layerX === 0) {
        mx= ev.layerX;
        my = ev.layerY;
    } else if (ev.offsetX || ev.offsetX === 0) {
        mx = ev.offsetX;
        my = ev.offsetY;
    }

    this.selectedObject = app.selectObject(mx, my);
}

// Remove Object
function remove() {
    let canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');
    app.removeObj();
    ctx.clearRect(0,0, canvas.width, canvas.height);
    app.drawObjects(canvas);
}

function setArea(){
    objDrawInCanvas = "Area";
}

function setArrow(){
    objDrawInCanvas = "Arrow";
}


//Drag & Drop operation
//drag
function dragObject(ev) {
    let canvas = document.getElementById('canvas');
    let mx = null;
    let my = null;
    let cnv = null;
    if ( ev.layerX ||  ev.layerX === 0) {
        mx= ev.layerX;
        my = ev.layerY;
    } else if (ev.offsetX || ev.offsetX === 0) {
        mx = ev.offsetX;
        my = ev.offsetY;
    }

    if (app.dragObject(mx, my)) {
        cnv = document.getElementById('canvas');
        cnv.addEventListener('mousemove', moveObject, false);
        cnv.addEventListener('mouseup', dropObject, false);
    }

    let rangeInput = document.getElementById('input-Rotation');
    rangeInput.value = 0;
}

//Drag & Drop operation
//move
function moveObject(ev) {
    let mx = null;
    let my = null;
    let cnv = document.getElementById('canvas');
    if ( ev.layerX ||  ev.layerX === 0) {
        mx= ev.layerX;
        my = ev.layerY;
    } else if (ev.offsetX || ev.offsetX === 0) {
        mx = ev.offsetX;
        my = ev.offsetY;
    }

    console.log(mx + " " + my + " MOVE_TESTE");

    app.moveObject(mx, my, cnv);
    let ctx = cnv.getContext('2d');
    ctx.clearRect(0,0,cnv.width, cnv.height);
    app.drawObjects(cnv);

}

//Drag & Drop operation
//drop
function dropObject() {
    let cnv = document.getElementById('canvas');
    cnv.removeEventListener('mousemove', moveObject, false);
    cnv.removeEventListener('mouseup', dropObject, false);
}

//Insert a new Object on Canvas
//dblclick Event
function newObject(ev) {
    let mx = null;
    let my = null;
    let drawCnv = document.getElementById('canvas');
    if (ev.layerX != null) {
        mx = ev.layerX;
        my = ev.layerY;
    } else if (ev.offsetX != null) {
        mx = ev.offsetX;
        my = ev.offsetY;
    }
    if (app.insertObject(mx,my, null)) {
        app.drawObjects(drawCnv);
    }
}

function newObjectFromButtonCircle(){
    let drawCnv = document.getElementById('canvas');
    let centerX = drawCnv.width/2;
    let centerY = drawCnv.height/2;

    let c = new Circle(centerX,centerY,20, hexToRgbA());

    if (app.insertObject(centerX,centerY,c)){
        app.drawObjects(drawCnv);
    }

    objDrawInCanvas = null;
}

function hexToRgbA() {
    let hex = document.getElementById('color-picker').value;
    let alpha = document.getElementById('color-picker-alpha').value;

    let c;
    if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
        c = hex.substring(1).split('');
        if (c.length === 3) {
            c = [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c = '0x' + c.join('');
        return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',' + alpha/100 + ')';
    }
}


// ===================================================================================
let coords = {};
let dragSquare = false;
let dragAndMove = false;
let dragArrow = false;

function initSquare(){
    let canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');

    canvas.addEventListener('mousedown', mouseDownSquareOrArrow, false);
    canvas.addEventListener('mousemove', mouseMoveSquareOrArrow, false);
    canvas.addEventListener('mouseup', mouseUpSquareOrArrow, false);
}

function mouseDownSquareOrArrow(e) {
    let canvas = document.getElementById('canvas');
    let r = canvas.getBoundingClientRect();
    let mx = null;
    let my = null;

    if ( e.layerX ||  e.layerX === 0) {
        mx= e.layerX;
        my = e.layerY;
    } else if (e.offsetX || e.offsetX === 0) {
        mx = e.offsetX;
        my = e.offsetY;
    }

    coords.pxInit = ((mx * canvas.width ) / r.width);
    coords.pyInit = ((my * canvas.height) / r.height);

    if (!app.dragObject(mx, my) && objDrawInCanvas === "Area") {
        dragSquare = true;
        dragAndMove = false;
    } else if(!app.dragObject(mx, my) && objDrawInCanvas === "Arrow"){
        dragArrow = true;
        dragAndMove = false;
    }
}

function mouseUpSquareOrArrow() {
    let canvas = document.getElementById('canvas');

    if (dragAndMove && dragSquare){
        drawSquare(canvas);
    } else if (dragAndMove && dragArrow){
        drawArrow(canvas);
    }
    dragArrow = false;
    dragSquare = false;
    dragAndMove = false;
}

function mouseMoveSquareOrArrow(e) {
    let canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');
    let r = canvas.getBoundingClientRect();
    dragAndMove = true;

    let mx = null;
    let my = null;
    if ( e.layerX ||  e.layerX === 0) {
        mx= e.layerX;
        my = e.layerY;
    } else if (e.offsetX || e.offsetX === 0) {
        mx = e.offsetX;
        my = e.offsetY;
    }

    if (dragSquare && dragSquare) {
        coords.w = ((mx * canvas.width ) / r.width) - coords.pxInit;
        coords.h = ((my * canvas.height) / r.height) - coords.pyInit;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        app.drawObjects(canvas);
        ctx.fillStyle = hexToRgbA();
        ctx.fillRect(coords.pxInit, coords.pyInit, coords.w, coords.h);
    } else if (dragAndMove && dragArrow){
        coords.pxEnd = ((mx * canvas.width ) / r.width);
        coords.pyEnd = ((my * canvas.height) / r.height);
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        app.drawObjects(canvas);
        ctx.beginPath();
        ctx.lineWidth = 5;
        ctx.fillStyle = "black";
        drawArrowInMove(ctx, coords.pxInit, coords.pyInit, coords.pxEnd, coords.pyEnd);
        ctx.stroke();
        ctx.closePath();
    }

}

function drawSquare(canvas) {
    let square = new Square(coords.pxInit, coords.pyInit, coords.w, coords.h, hexToRgbA());
    if (app.insertObject(coords.pxInit, coords.pyInit, square)){
        app.drawObjects(canvas);
    }
}

function drawArrow(canvas) {
    let arrow = new Arrows(coords.pxInit, coords.pyInit, coords.pxEnd, coords.pyEnd, hexToRgbA());
    if (app.insertObject(coords.pxInit, coords.pyInit, arrow)){
        app.drawObjects(canvas);
    }
}

function drawArrowInMove(context, fromX, fromY, toX, toY) {
    let headlen = 30; // length of head in pixels
    let dx = toX - fromX;
    let dy = toY - fromY;
    let angle = Math.atan2(dy, dx);
    context.moveTo(fromX, fromY);
    context.setLineDash([20]);
    context.lineTo(toX, toY);
    context.setLineDash([0]);
    context.lineTo(toX - headlen * Math.cos(angle - Math.PI / 6), toY - headlen * Math.sin(angle - Math.PI / 6));
    context.moveTo(toX, toY);
    context.setLineDash([0]);
    context.lineTo(toX - headlen * Math.cos(angle + Math.PI / 6), toY - headlen * Math.sin(angle + Math.PI / 6));
}

function insertImage() {
    let canvas = document.getElementById('canvas');
    let centerX = canvas.width/2;
    let centerY = canvas.height/2;

    let e = document.getElementById("cones");
    let strCones = e.value;

    let img = null;

    switch (strCones) {
        case "red":
            img = new Picture(centerX,centerY,40,25, 'img/cones/coneVermelho.png', "CONE", "0");
            break;
        case "yellow":
            img = new Picture(centerX,centerY,40,25, 'img/cones/coneAmarelo.png', "CONE", "0");
            break;
        case "orange":
            img = new Picture(centerX,centerY,40,25, 'img/cones/coneLaranja.png', "CONE", "0");
            break;
        case "blue":
            img = new Picture(centerX,centerY,40,25, 'img/cones/coneAzul.png', "CONE", "0");
            break;
    }

    if (app.insertObject(centerX,centerY,img)){
        app.drawObjects(canvas);
    }

    objDrawInCanvas = null;
}

function insertGoal() {
    let canvas = document.getElementById('canvas');
    let centerX = canvas.width/2;
    let centerY = canvas.height/2;

    let e = document.getElementById("cones");
    let strCones = e.value;

    let img = new Picture(centerX,centerY,100,35, 'img/Goals/goal0.png', "GOAL","0");

    if (app.insertObject(centerX,centerY,img)){
        app.drawObjects(canvas);
    }

    objDrawInCanvas = null;
}

function sliderUpdate(){
    let canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');
    let rangeInput = document.getElementById('input-Rotation');

    let value = rangeInput.value;

    let urlImg = null;

    switch (value){
        case "90":
            urlImg = "img/Goals/goal90.png";
            app.rotateObject(canvas, urlImg, value);
            break;
        case "180":
            urlImg = "img/Goals/goal180.png";
            app.rotateObject(canvas, urlImg, value);
            break;
        case "270":
            urlImg = "img/Goals/goal270.png";
            app.rotateObject(canvas, urlImg, value);
            break;
        case "0":
        case "360":
            urlImg = "img/Goals/goal0.png";
            app.rotateObject(canvas, urlImg, value);
            break;
    }
}

function setBackgroundCanvas(canvas, img, counter){
    let context = canvas.getContext('2d');
    let background = new Image();
    let url = "backgroundCanvas-row_" + counter;
    let backgroundCanvasImg = document.getElementById(url);
    background.src = img;

    background.onload = function (){
        backgroundCanvasImg.style.backgroundImage = 'none';
        context.clearRect(0, 0, canvas.width, canvas.height);
        context.drawImage(background, 0, 0, canvas.width, canvas.height);
        app.drawObjects(canvas);
        let imgCanvas = canvas.toDataURL('image/png');
        let imgTag = document.createElement("img");
        imgTag.src = imgCanvas;
        backgroundCanvasImg.appendChild(imgTag);

        let inputHidden = document.createElement("input");
        inputHidden.setAttribute("type", "hidden");
        inputHidden.setAttribute("id", "inputCanvasImag_" + counter.toString());
        inputHidden.setAttribute("name", "inputCanvasImag_" + counter.toString());
        inputHidden.setAttribute("value", imgCanvas);

        //append to form element that you want .
        backgroundCanvasImg.appendChild(inputHidden);

        inputHidden = document.createElement("input");
        inputHidden.setAttribute("type", "hidden");
        inputHidden.setAttribute("id", "inputCounter");
        inputHidden.setAttribute("name", "inputCounter");
        inputHidden.setAttribute("value", counter);

        //append to form element that you want .
        backgroundCanvasImg.appendChild(inputHidden);
    }
}