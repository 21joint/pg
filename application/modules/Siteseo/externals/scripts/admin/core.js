//CLASS TO EDIT A TABLE INLINE BY DOUBLE CLICKING THE REQUIRED ELEMENT
var InlineEdit = new Class({
	data: {
		table : null,
		editableColumns: null,
		primaryKey: null,
		url: null,
	},
	columnNumber:null,
	tablekeys: {},
	div: null,
	primaryKey: null,
	textarea: null,
	target: null,
	elementValue : '',
	opened: false,
	params: {},
	savingStatus: null,
	//GET TABLE OBJECT AND COLUMNS CLASSES 
	initialize: function (data) {
		this.data = data;
		this.div = this.data.table ? this.data.table.getElement('#edit-inline-div') : null;
		this.textarea = this.div ? this.div.getElement('textarea#inline-edit-textarea') : null;
		this.createElements();
		this.addClickEvents();
	},
	createElements: function() {
		if (!this.savingStatus)
			this.savingStatus = new Element('div', {
				'id' : 'inline-edit-saving-div',
				'style' : 'display:none;top:0%;width:100%;height:100%;left:0%;position:fixed;z-index:99;text-align:center;'
			}).inject(this.data.table,'before')

		var statusDiv = new Element('div', {
			'id' : '',
			'style' : 'top:40%; position:fixed; left:45%; z-index:99; padding:10px 30px; background:#dddddd; display:inline; margin-right:5%;'
		}).inject(this.savingStatus);

		var span = new Element('span', {
			'id' : 'inline-edit-saving-div-span',
			'html' : 'Saving Data .. '
		}).inject(statusDiv);

		var img = new Element('img', {
			'id' : 'inline-edit-saving-div-img',
			'src' : en4.core.staticBaseUrl + 'application/modules/Core/externals/images/loading.gif',
			'style' : 'margin-left:10px;'
		}).inject(statusDiv);
	},
	addClickEvents: function() {
	    var self = this;
	    var editIconPath = 'application/modules/Siteseo/externals/images/admin/edit.png';
	    //DOUBLE CLICK EVENTS ON EDITABLE TABLE CELLS
		Object.keys(this.data.editableColumns).each(function(columnNumber) {
			items = this.data.table.getElements('tr td:nth-child('+ columnNumber +')');
			items.each(function(item) {

				text = item.get('text');
				var editIcon = new Element('img' , {src: editIconPath, class: 'edit-inline-pencil-icon', title: 'Edit'})
				var span = new Element('span', {class : 'td-data', text : text});
				item.set('html','');
				editIcon.inject(item);
				span.inject(item);

				editIcon.addEvent('click', function() {
					target = event.target.getParent();
					if (self.opened) {
						if (self.elementValue == self.textarea.value) {
							self.hideEditBox();
							self.showEditBox(target);
						} else {
							self.sendRequest(target);
						}
					} else {
						self.showEditBox(target);
					}
				});

			})
		})
	},
	showEditBox: function(target) {
		self = this;
		this.target = target;

		self.data.primaryKey.each(function(key) {
			self.tablekeys[key] = self.target.getParent('tr').get(key);
		})
		var i = 0;
		while (this.target.getParent('tr').children[i] != this.target) i++;
		this.columnNumber =  i + 1;

		this.opened = true;
		this.div = new Element('div', {'id' : 'edit-inline-div'});
		this.textarea = new Element('textarea' ,{'id' : 'inline-edit-textarea'}).inject(this.div);
		var saveIconPath = 'application/modules/Siteseo/externals/images/admin/save.png';
		var saveIcon = new Element('img' , {src: saveIconPath, class: 'edit-inline-save-icon', title: 'Save'}).inject(this.div);

		// ADD EVENT TO SAVE ICON
		saveIcon.addEvent('click', function() {
			if (self.elementValue == self.textarea.value)
				self.hideEditBox();
			else
				self.sendRequest();
		});
		this.textarea.setStyles({
			'width' : this.target.getStyle('width'),
			'height': this.target.getStyle('height')
		});
		this.div.inject(this.target)
		this.target.getElement('span.td-data').hide();
		this.textarea.value = this.elementValue = this.target.get('text').trim();
		this.target.getChildren('.edit-inline-pencil-icon').hide();
	},
	sendRequest: function(target) {
	    var self = this;
	    this.columnValue = this.data.editableColumns[this.columnNumber];
	    this.params = {
	    	'key' : JSON.stringify(this.tablekeys),
	    	'column' : this.columnValue,
	    	'value' : this.textarea.value,
	    }
	    en4.core.request.send(new Request.JSON({
	    	url: self.data.url,
	    	data: self.params,
	    	onRequest: function () {
	    		self.savingStatus.setStyle('display','block');
	    	},
	    	onSuccess: function (responseJSON) {
	    		self.savingStatus.setStyle('display','none');
	    		self.hideEditBox();
	    		if (target) {
	    			self.showEditBox(target);
	    		}
	    	}
	    }));
	},
	hideEditBox: function() {
		textSpan = this.target.getChildren('.td-data');
		editIcon = this.target.getChildren('.edit-inline-pencil-icon');
		editIcon.show();
		textSpan.set('text', this.textarea.value);
		this.target.getElement('span.td-data').show();
		this.elementValue = this.textarea.value;
		this.div.destroy()
		this.opened = false;
	},
});
