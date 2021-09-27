class DrawObjects{

    constructor (px, py, name) {
        if (this.constructor === DrawObjects) {
            // Error Type 1. Abstract class can not be constructed.
            throw new TypeError("Can not construct abstract class.");
        }

        //else (called from child)
        // Check if all instance methods are implemented.
        if (this.draw === DrawObjects.prototype.draw) {
            // Error Type 4. Child has not implemented this abstract method.
            throw new TypeError("Please implement abstract method draw.");
        }
        if (this.mouseOver === DrawObjects.prototype.mouseOver) {
            // Error Type 4. Child has not implemented this abstract method.
            throw new TypeError("Please implement abstract method mouseOver.");
        }
        this.posx = px;
        this.posy = py;
        this.name = name;
    }

    draw (cnv) {
        // Error Type 6. The child has implemented this method but also called `super.foo()`.
        throw new TypeError("Do not call abstract method draw from child.");
    }

    mouseOver(mx, my) {
        // Error Type 6. The child has implemented this method but also called `super.foo()`.
        throw new TypeError("Do not call abstract method mouseOver from child.");
    }

    sqDist(px1, py1, px2, py2) {
        let xd = px1 - px2;
        let yd = py1 - py2;
        return ((xd * xd) + (yd * yd));
    }

    setPos(mx,my){
        this.posx=mx;
        this.posx=my;
    }
}

class Circle extends DrawObjects {

    constructor(px, py, radius, color ){
        super(px, py, "Circle");
        this.posx = px;
        this.posy = py;
        this.radius = radius;
        this.color = color;

    }

    mouseOver(mouseX, mouseY){
        let canvas = document.getElementById('canvas');
        var rect = canvas.getBoundingClientRect();

        let auxPosX = (this.posx*rect.width)/canvas.width;
        let auxPosY = (this.posy*rect.height)/canvas.height;
        let x1 = 0;
        let y1 = 0;
        let x2 = (mouseX - auxPosX);
        let y2 = (mouseY - auxPosY);
        let auxRadius = (this.radius * rect.width) / canvas.width;
        return (auxPosX - mouseX)*(auxPosX - mouseX) + (auxPosY-mouseY)*(auxPosY-mouseY) <= (auxRadius*auxRadius);
    }

    draw(canvas){
        let context = canvas.getContext('2d');
        context.beginPath();
        context.lineWidth = 5;
        context.fillStyle = this.color;
        context.arc(this.posx, this.posy, this.radius, 0, 2 * Math.PI, false);
        context.stroke();
        context.fill();
        context.closePath();
    }
}

class Square extends DrawObjects {
    constructor (px, py, w, h, c) {
        super(px, py, 'Square');
        this.px = px;
        this.py = py;
        this.w = w;
        this.h = h;
        this.color = c;
    }

    mouseOver(mouseX, mouseY) {
        return false;
    }

    draw (cnv) {
        let context = cnv.getContext("2d");
        context.save();
        context.lineWidth = 5;
        context.fillStyle = "black";
        context.setLineDash([20]);
        context.fillStyle = this.color;
        context.strokeRect(this.posx, this.posy, this.w, this.h);
        context.fillRect(this.posx, this.posy, this.w, this.h);
        context.restore();
    }
}

class Arrows extends DrawObjects{
    constructor (pxInit, pyInit, pxEnd, pyEnd, color) {
        super(pxInit, pyInit, 'Arrow');
        this.pxInit = pxInit;
        this.pyInit = pyInit;
        this.pxEnd = pxEnd;
        this.pyEnd = pyEnd;
        this.color = color;
    }

    mouseOver(mouseX, mouseY) {
        return false;
    }
    draw (cnv) {
        let context = cnv.getContext("2d");
        context.beginPath();
        context.lineWidth = 5;
        context.fillStyle = this.color;
        this.drawArrow(context, this.pxInit, this.pyInit, this.pxEnd, this.pyEnd);
        context.stroke();
        context.closePath();
    }

    drawArrow(context, fromX, fromY, toX, toY) {
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
}

class Picture extends DrawObjects {
    constructor (px, py, w, h, impath, type, rotation) {
        super(px, py, 'Picture');
        this.type = type;
        this.posx = px;
        this.posy = py;
        this.rotation = rotation;
        this.wdt = w;
        this.hgt = h;
        this.imgPath = impath;
        this.imgobj = new Image();
        this.imgobj.src = impath;
    }

    mouseOver(mx, my) {
        let canvas = document.getElementById('canvas');
        let rect = canvas.getBoundingClientRect();

        let auxPosX = (this.posx * rect.width) / canvas.width;
        let auxPosY = (this.posy * rect.height) / canvas.height;
        let auxPosW = (this.wdt * rect.width) / canvas.width;
        let auxPosH = (this.hgt * rect.height) / canvas.height;

        return ((mx >= auxPosX) && (mx <= (auxPosX + auxPosW)) && (my >= auxPosY) && (my <= (auxPosY + auxPosH)));
    }

    draw (cnv) {
        let ctx = cnv.getContext("2d");
        if (this.imgobj.complete) {
            ctx.drawImage(this.imgobj, this.posx, this.posy, this.wdt, this.hgt);
        } else {
            console.log("Debug: First Time");
            let self = this;
            this.imgobj.addEventListener('load', function () {
                ctx.drawImage(self.imgobj, self.posx, self.posy, self.wdt, self.hgt);
            }, false);
        }
    }
}