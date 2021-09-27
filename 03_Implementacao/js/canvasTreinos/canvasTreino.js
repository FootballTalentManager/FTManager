class CanvasTreino {

    constructor() {
        this.thingInMotion = null;
        this.offsetx = null;
        this.offsety = null;
        this.drawingPool = new Pool(100);
    }

    init(){}

    selectObject(mx,my){
        for (let object of this.drawingPool.stuff)
            if(object.mouseOver(mx,my)){
                return this.cloneObject(object);
            }
        return null;
    }

    removeObj () {
        this.drawingPool.remove();
    }

    //desenha os objectos no canvas
    drawObjects(canvas){
        for (let i = 0; i < this.drawingPool.stuff.length; i++) {
            this.drawingPool.stuff[i].draw(canvas);
        }
    }

    rotateObject(canvas, urlImage, rotation){
        if (this.drawingPool.stuff.length > 0){
            let objRotate = this.drawingPool.stuff[this.drawingPool.stuff.length - 1];

            let item = null;
            let ctx = canvas.getContext('2d');
            let wdt = 35;
            let hgt = 100;
            if (objRotate.name === "Picture" && objRotate.type === "GOAL"){
                if ((rotation === "90" || rotation === "270")){
                    item = new Picture(objRotate.posx, objRotate.posy, wdt, hgt, urlImage, objRotate.type, rotation);
                } else {
                    item = new Picture(objRotate.posx, objRotate.posy, hgt, wdt, urlImage, objRotate.type, rotation);
                }

                this.removeObj();
                this.insertObject(objRotate.posx, objRotate.posy, item);
                ctx.clearRect(0,0, canvas.width, canvas.height);
                app.drawObjects(canvas);
            }
        }
    }

    dragObject(mouseX, mouseY){
        let stuffSize = this.drawingPool.stuff.length - 1;
        for (let i = stuffSize; i >= 0; i--) {
            //verificar se esta por cima de um dos objectos
            if (this.drawingPool.stuff[i].mouseOver(mouseX, mouseY)){
                this.offsetx = 0;
                this.offsety = 0;
                let item = this.drawingPool.stuff[i];
                //para o objecto ser o ultimo da lista, facilitando o movimento do mesmo
                this.thingInMotion = this.drawingPool.stuff.length - 1;
                this.drawingPool.stuff.splice(i, 1);
                this.drawingPool.stuff.push(item);
                return true;
            }
        }
        return false;
    }

    moveObject(mouseX, mouseY, canvas){
        let rect = canvas.getBoundingClientRect();

        this.drawingPool.stuff[this.thingInMotion].posx = ((mouseX * canvas.width ) / rect.width) - this.offsetx;
        this.drawingPool.stuff[this.thingInMotion].posy = ((mouseY * canvas.height) / rect.height) - this.offsety;
    }

    removeObject(){
        this.drawingPool.remove();
    }

    insertObject(posX, posY, itemFromButton){
        let item = null;
        let stuffSize = this.drawingPool.stuff.length - 1;

        if (itemFromButton === null){
            //se ja tiver um objecto na pool e clicarmos em cima dele, vai ser clonado
            for (let i = stuffSize; i >= 0; i--) {
                if (this.drawingPool.stuff[i].mouseOver(posX,posY)) {
                    item = this.cloneObject(this.drawingPool.stuff[i]);
                    this.drawingPool.insert(item);
                    return true;
                }
            }
        } else {
            let insertObj = this.cloneObject(itemFromButton);
            if (itemFromButton.name === "Square")
                this.drawingPool.insertSquare(itemFromButton);
            else
                this.drawingPool.insert(insertObj);
            return true;
        }
    }

    cloneObject(object){
        let item = null;
        let color = object.color;

        switch (object.name){
            case 'Circle':
                console.log(object.posx + " " + object.posy + " clone");
                item = new Circle(object.posx, object.posy, object.radius, color);
                break;
            case 'Square':
                console.log(object.posx + " " + object.posy + " clone");
                item = new Square(object.px, object.py, object.w, object.h, color);
                break;
            case 'Arrow':
                console.log(object.posx + " " + object.posy + " clone");
                item = new Arrows(object.pxInit, object.pyInit, object.pxEnd, object.pyEnd, color);
                break;
            case 'Picture':
                console.log(object.posx + " " + object.posy + " clone");
                item = new Picture(object.posx, object.posy, object.wdt, object.hgt, object.imgPath, object.type, object.rotation);
                break;
        }
        return item;
    }
}

class Pool{
    constructor (maxSize) {
        this.size = maxSize;
        this.stuff = [];
    }

    insertSquare(obj){
        if (this.stuff.length < this.size) {
            this.stuff.unshift(obj);
        } else {
            alert("The application is full: there isn't more memory space to include objects");
        }
    }

    insert(obj) {
        if (this.stuff.length < this.size) {
            this.stuff.push(obj);
        } else {
            alert("The application is full: there isn't more memory space to include objects");
        }
    }

    remove() {
        if (this.stuff.length !== 0) {
            this.stuff.pop();
        } else {
            alert("There aren't objects in the application to delete");
        }
    }
}