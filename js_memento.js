/**
 * JS memento
 */

// Literal object constructor
var alice = {
    name: "Alice",
    age: 30
};

// Pre-built constructors
var alice = new Object();
var contacts = new Array();

// Custom constructor:
function Person (name, age) {
    this.name = name;
    this.age = age;
}
var alice = new Person("Alice", 30);

// Selectors
contacts[i];
alice.name;
alice["age"];

// Function
var ageDifference = function(person1, person2) {
    return person1.age - person2.age;
}
var diff = ageDifference(alice, billy);

// While loop
while(i < 50) {
	//do stuff
}
// Do/While loop
do {
    text += "The number is " + i;
    i++;
}
while (i < 10);

// For loop
for(i = O; i < contacts.length; i++) {
	txt += contacts[i]; //cool for arrays
}
// For/in loop
for (x in object) {
    txt += object[x] + " "; //cool for objects
}

// Conditions
if(condition) {
	//do stuff
} else if {
	//do some other stuff
} else {
	//do something else
}
// Switch cases
switch(name) {
	case(name === "George"):
		// do something
		break;
	case(name === "Josh"):
		//do something else
		break;
	default:
		//default option here
}

// Math
var x = Math.random();
var pi = Math.PI();

alert("This will pop up");
prompt("User input", "Textbox here");
document.write("Stuff");
document.getElementById("direct-en-cours").innerHTML = "Stuff";
console.log("Some string" + variable);
