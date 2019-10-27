<!DOCTYPE HTML>
<html>
<head>
<style>
.drop {
	min-width: 200px;
	min-height: 200px;
	border: 1px solid #aaaaaa;
}
.task {
	width: 100%;
	min-height: 70px;
	border: 1px solid #aaaaaa;
}

/* The Modal (background) */
.modal {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
	background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
	background-color: #fefefe;
	margin: 15% auto; /* 15% from the top and centered */
	padding: 20px;
	border: 1px solid #888;
	width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
	color: #aaa;
	float: right;
	font-size: 28px;
	font-weight: bold;
}

.close:hover,
.close:focus {
	color: black;
	text-decoration: none;
	cursor: pointer;
}

.collapsible {
	background-color: #777;
	color: white;
	cursor: pointer;
	padding: 18px;
	width: 100%;
	border: none;
	text-align: left;
	outline: none;
	font-size: 15px;
}

.active, .collapsible:hover {
	background-color: #555;
}

.content {
	padding: 0 18px;
	display: none;
	overflow: hidden;
	background-color: #f1f1f1;
}

td {
	border-bottom: 2px solid #000;
}
</style>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>

<body>
	<button type="button" class="collapsible">Sprint #1 (Back to work): 28.10.2019 - 03.11.2019</button>
	<div class="content">
		<br>
		<button onclick="showModal(0, -1)">Create Story</button>

		<table id="stories" style="width:100%">
			<tr>
				<th>Story</th>
				<th>Tasks</th>
				<th>Doing</th>
				<th>Review</th>
				<th>Done</th>
			</tr>
		</table>
	</div>

	<!-- The Modal -->
	<div id="myModal" class="modal">

		<!-- Modal content -->
		<div class="modal-content">
			<span class="close">&times;</span>
			<div id="content">Some text in the Modal..</div>
		</div>
	</div>
</body>
</html>


<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
	coll[i].addEventListener("click", function() {
		this.classList.toggle("active");
		var content = this.nextElementSibling;
		if (content.style.display === "block") {
			content.style.display = "none";
		} else {
			content.style.display = "block";
		}
	});
}


function allowDrop(ev) {
	ev.preventDefault();
}

function drag(ev) {
	ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
	ev.preventDefault();
	var data = ev.dataTransfer.getData("text");
	ev.target.appendChild(document.getElementById(data));
	console.log("Dropped " + data + " at " + ev.target.id);

	editTask(data, ev.target.id);
}


// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// type: 0 = new story, 1 = new task
function showModal(type, story) {
	var content = "";

	// New Story
	if (type == 0)
	{
		content = "Storyname: <input type=\"text\" id=\"storyName\"><br>";
		content += "Storydescription: <input type=\"text\" id=\"storyDesc\"><br>";
		content += "<button onclick=\"saveStory(-1, " + story + ")\">Save</button>";
	}

	// New Task
	if (type == 1)
	{
		content = "Taskname: <input type=\"text\" id=\"taskName\"><br>";
		content += "Storydescription: <input type=\"text\" id=\"taskDesc\"><br>";
		content += "<button onclick=\"saveTask(-1, " + story + ")\">Save</button>";
	}
	console.log(content);

	$('#content').html(content);
	modal.style.display = "block";
}

function hideModal()
{
	modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
	modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}


getStory();
getTask();

// id = story id
function saveStory(id = -1)
{
	if (id == -1)
	{
		var name = $('#storyName').val();
		var description = $('#storyDesc').val();
		var save = true;
		addStory(id, name, description, save);
	}
	hideModal();
}

function addStory(id, name, description, save = false)
{
	if (save == true)
	{
        $.ajax({
            url: "add_story.php",
            type: "post",
            data: { name : name, description : description },
            success: function(result)
            {
            	console.log(result);
            	var story = JSON.parse(result);
            	id = story['id'];

            	addStory(id, name, description);
            }
        });
        return;
	}

	var str = "<tr>";
	str += "<td>";
	str += "<div id=\"story\" class=\"drop\">";
	str += "<button>^</button>";
	str += "<button>v</button>";
	str += "<button>Edit Story</button>";
	str += "<h2 id=\"story" + id + "\">" + name + "</h2>";
	str += "<p id=\"storyDesc" + id + "\">" + description + "</p>";
	str += "</div>";
	str += "</td>";
	str += "<td>";
	str += "<div id=\"tasks" + id + "\" class=\"drop\" ondrop=\"drop(event)\" ondragover=\"allowDrop(event)\">";
	str += "<button onclick=\"showModal(1, " + id + ")\">Create Task</button>";
	str += "</div>";
	str += "</td>";
	str += "<td>";
	str += "<div id=\"doing" + id + "\" class=\"drop\" ondrop=\"drop(event)\" ondragover=\"allowDrop(event)\"></div>";
	str += "</td>";
	str += "<td>";
	str += "<div id=\"review" + id + "\" class=\"drop\" ondrop=\"drop(event)\" ondragover=\"allowDrop(event)\"></div>";
	str += "</td>";
	str += "<td>";
	str += "<div id=\"done" + id + "\" class=\"drop\" ondrop=\"drop(event)\" ondragover=\"allowDrop(event)\"></div>";
	str += "</td>";
	str += "</tr>";
	$('#stories tr:last').after(str);
}

function getStory(id = -1)
{
	$.ajax({
        url: "get_story.php",
        type: "post",
        data: { },
        success: function(result)
        {
            var stories = JSON.parse(result);
            console.log(stories);

            for (var i = stories.length - 1; i >= 0; i--)
            {
				addStory(stories[i]['id'], stories[i]['name'], stories[i]['description']);
            }
        }
    });
}

// id = task id
function saveTask(id = -1, story)
{
	if (id == -1)
	{
		var name = $('#taskName').val();
		var description = $('#taskDesc').val();
		var status = 0;
		var save = true;
		addTask(id, name, description, story, status, save);
	}
	hideModal();
}

function addTask(id, name, description, story, status, save = false)
{
	if (save == true)
	{
		console.log(name, description, story);

        $.ajax({
            url: "add_task.php",
            type: "post",
            data: { name : name, description : description, story : story },
            success: function(result)
            {
            	console.log(result);

            	var task = JSON.parse(result);
            	id = task['id'];
            	status = 0;
            	
            	addTask(id, name, description, story, status);
            }
        });
        return;
	}

	var str = "<div id=\"task" + id + "\" class=\"task\" draggable=\"true\" ondragstart=\"drag(event)\" ondragover=\"\">";
	str += "<h3>" + name + "</h3>";
	str += "<p>" + description + "</p>";
	str += "</div>";

	var div = "";
	if (status == 0) div = "#tasks" + story;
	if (status == 1) div = "#doing" + story;
	if (status == 2) div = "#review" + story;
	if (status == 3) div = "#done" + story;

	console.log(div);

	$(div).append(str);
}

function getTask(id = -1)
{
	$.ajax({
        url: "get_task.php",
        type: "post",
        data: { },
        success: function(result)
        {
            var tasks = JSON.parse(result);

            for (var i = tasks.length - 1; i >= 0; i--)
            {
				addTask(tasks[i]['id'], tasks[i]['name'], tasks[i]['description'], tasks[i]['story'], tasks[i]['status']);
            }
        }
    });
}

function editTask(id, target)
{
	id = id.split("task")[1];
	var status = 0;

	if (target.includes("tasks")) status = 0;
	if (target.includes("doing")) status = 1;
	if (target.includes("review")) status = 2;
	if (target.includes("done")) status = 3;

	$.ajax({
        url: "edit_task.php",
        type: "post",
        data: { id : id, status : status },
        success: function(result)
        {
        }
    });
}
</script>