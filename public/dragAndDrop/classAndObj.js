console.log('----------- 1 -----------')

class Point {
	constructor (x, y) {
		// console.log(this)
		
		this.x = x
		this.y = y
	}

	getDist (point) {
		return ((this.x - point.x)**2 + (this.y - point.y)**2)**0.5
	}

	get fullCoordinats () {
		return `(${this.x}; ${this.y})`
	}

	set fullCoordinats (str) {
		const coordinats = str.slice(1, -1).split('; ').map(Number)

		this.x = coordinats[0]
		this.y = coordinats[1]

		return str
	}

	static getDist (a, b) {
		return ((a.x - b.x)**2 + (a.y - b.y)**2)**0.5
	}
}

const pointA = new Point(100, 25)
const pointB = new Point(30, 12)

console.log({ pointA })
console.log(pointA.fullCoordinats)

pointA.fullCoordinats = '(0; -100)'
console.log({ pointA })

console.log(pointA.getDist(pointB))
console.log(Point.getDist(pointA, pointB))



console.log('----------- 2 -----------')
// Наследование

class Point {
	constructor(x, y) {
		this.x = x
		this.y = y
	}

	dist(point) {
		return ((this.x - point.x) ** 2 + (this.y - point.y) ** 2) ** 0.5
	}
}

class Vector extends Point {
	constructor(x, y, dx, dy) {
		super(x, y)

		this.dx = dx
		this.dy = dy
	}

	get length() {
		return super.dist({
			x: this.dx,
			y: this.dy
		})
	}
}

const point = new Point(0, 0)
const vector = new Vector(0, 0, 5, 4)

console.log(vector)

console.log(vector.dist(point))



console.log('----------- 3 -----------')

person = createPerson('Алексей', 'Данчин')
console.log({ person })

function createPerson(name, family) {
	const person = {
		name: name,
		family: family,
		sayHello() {
			console.log(`Привет! Меня зовут ${this.name} ${this.family}`)
		}
	}

	return person
}



console.log('----------- 4 -----------')

person = new Person('Алексей', 'Данчин')
console.log({ person })

function Person(name, family) {
	this.name = name
	this.family = family

	this.sayHello = () => {
		console.log(`Привет! Меня зовут ${this.name} ${this.family}`)
	}
}



console.log('----------- 5 -----------')

person = {
	name: 'Алексей',
	family: 'Данчин',
	age: 26,

	get fullName() {
		return `${this.name} ${this.family}`
	},

	set fullName(str) {
		const pair = str.split(' ')

		this.name = pair[0]
		this.family = pair[1]

		return str
	}
}
