var TodoList = {
	tasks: [
		{name:'test1', isDone: false},
		{name:'test3', isDone: true}
	],
	listElement:null,
	init: function(){
		this.listElement=$('#tasks');
		this.buildList();
		this.setupEvents();
	},
	
	// builds TODO list
	buildList: function(){
		this.listElement.html('');
		
		$.each(this.tasks, function(k, task){
			var item=$('<li></li>');
			$('<div></div>').html(task.name).addClass('name').addClass(task.isDone?'done':'').appendTo(item);
			
			$('<input type="text" />').val(task.name).addClass('hidden').appendTo(item);
			if (!task.isDone) {
				//Done button		
				$('<a></a>').html('Done').addClass('button is-small is-primary done-btn').attr('data-id', k).on('click', function (event) {
					event.preventDefault();
					TodoList.markAsDone($(this).attr('data-id'));
				}).appendTo(item);
			}
			//Delete button
			$('<a></a>').html('Del').addClass('button is-small is-danger delete-btn').attr('data-id', k).on('click', function (event) {
				event.preventDefault();
				TodoList.removeTask($(this).attr('data-id'));
			}).appendTo(item);
			if (!task.isDone) {
				
				// Edit button
				$('<a></a>').html('Edit').addClass('button is-small is-warning edit-btn').on('click', function (event) {
					event.preventDefault();
						TodoList.editMode($(this).parent());
				}).appendTo(item);
			
				// Save button
				$('<a></a>').html('Save').addClass('button is-small is-info hidden save-btn').attr('data-id', k).on('click', function (event) {
					event.preventDefault();
					TodoList.saveTask($(this).attr('data-id'), $('input', $(this).parent()).val());
						TodoList.editMode($(this).parent());
				}).appendTo(item);
				
				// Cancel button
				$('<a></a>').html('Cancel').addClass('button is-small hidden cancel-btn').on('click', function (event) {
					event.preventDefault();
						TodoList.editMode($(this).parent());
				}).appendTo(item);
			}
			TodoList.listElement.append(item);
		});
	},
	// disables/ables form elements
	editMode: function (liElement) {
		$('div.name', liElement).toggleClass('hidden');
		$('a.delete-btn', liElement).toggleClass('hidden');
		$('a.edit-btn', liElement).toggleClass('hidden');
		$('a.done-btn', liElement).toggleClass('hidden');
		
		$('a.save-btn', liElement).toggleClass('hidden');
		$('a.cancel-btn', liElement).toggleClass('hidden');
		$('input', liElement).toggleClass('hidden');
	},
	// setups tasks to TODO list
	setupEvents: function(){
		$('#add-task').on('click',function(event){
			event.preventDefault();
			TodoList.addTask($('#task-name').val());
			$('#task-name').val('');
		});
	},
	
	// marks tasks done
	markAsDone: function (id) {
		this.tasks[id].isDone= true;
		this.buildList();	
		
	},
	
	//adds new tasks to TODO list
	addTask: function(name){
		this.tasks.push({
			name:name,
			isDone: false
		});
		this.buildList();
	},
	
	// saves tasks to TODO list
	saveTask: function (id, name) {
		this.tasks[id].name = name;
		this.buildList();
	},
	
	//removes task from TODO list 
	removeTask: function (id){
		this.tasks.splice(id, 1);
		this.buildList();
	}
};

$( document ).ready(function() {
    TodoList.init();
});