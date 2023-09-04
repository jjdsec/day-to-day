// create page
var page = document.createElement("div");
page.className = "page";
page.name = "Tasks";
page.appendChild(document.createTextNode("this is the tasks page"));
document.getElementById("page-view").appendChild(page);
// console.log("Created page 'tasks'\n", page)

var pageTitle = document.createElement('h2');
pageTitle.appendChild(document.createTextNode("To Do List"))
page.appendChild(pageTitle)

// create menu entry
var menuEntry = document.createElement('img')
menuEntry.setAttribute("src", "/app/assets/img/tasks.png")
menuEntry.setAttribute("alt", "tasks")
menuEntry.addEventListener('click', () => { changePage('Tasks'); })
document.getElementById("bottom-tabs").appendChild(menuEntry)