$(document).ready(function() {
    var timerId;
    let options = {
        placeholder: "ui-state-highlight",
        //axis: "y",
        items: "> li",
        //containment: "parent",
        connectWith: ".sort",
        forcePlaceholderSize: true,
        cursor: "move",
        //helper: "clone",
        //dropOnEmpty: true,
        update: function(event, ui) {
            let collector = get_item_attr($(ui.item[0]));
            add_cancel_tooltip($(ui.item[0]));
            send_reorder_data(collector);
        }
    };

    /* get saved menu items from localstorage */
    get_expanded(options);

    $('.struct').on('click', '.ctrl-arrow', function() {
        let parent = $(this).closest('li');
        let id = parent.data('id');
        let _that = $(this);

        if (!parent.hasClass('expand')) {
            $.post('/struct/show/' + id, function (data) {
                parent.append(data);
                let sort = parent.find('.sort');
                sort.sortable(options);
                let expanded = _that.closest('.struct').children('li').children('ul').children('.expand');
                save_expanded(expanded);
            });
        } else {
            parent.find('ul').remove();
            parent.find('.pager').remove();
            remove_expanded(id);
        }

        parent.toggleClass('expand');

    });

    $('.struct').on('click', '.pager>.pager-item', function() {
        let _that = $(this);
		pager_handler(_that);
    });

    $('.struct').on('mouseenter', '.pager>.pager-item', function() {
        let _that = $(this);
		if (_that.hasClass('active')) {
			return;
		}
		
        timerId  = setTimeout(function() {
            pager_handler(_that);
        }, 2000);
    });

    $('.struct').on('mouseleave', '.pager>.pager-item', function() {
        clearInterval(timerId);
    });

    function pager_handler(_that) {
        let offset = _that.data('id');
        let parent = _that.closest('.expand');
        let id = parent.data('id');
        let inst = _that.closest('.expand').find('.sort').sortable('instance');
		let placeholder = inst.options.placeholder;

        let clone = "";

        if (typeof(id) == 'undefined')
            return;

        $.post('/struct/show/' + id + '/' + offset, function (data) {
            parent.find('ul li:not(.ui-sortable-helper)').remove();
            parent.find('.pager .pager-item').remove();
			parent.find('.pager').append($($(data)[0]).find('.pager-item'));
            parent.find('.sort').append($($(data)[2]).find('li'));

            // if (typeof (inst) != "undefined") {
                // if (typeof (inst.currentItem) != "undefined") {
                    // clone = inst.currentItem.clone();
                // }
            // }

            let sort = parent.find('.sort');
			let new_inst = sort.sortable('instance');
			new_inst.options.placeholder = inst.options.placeholder;
			console.log(inst.options.placeholder);
            sort.sortable(options);
            let expanded = parent.closest('.struct').children('li').children('ul').children('.expand');
            save_expanded(expanded);
        });
    }

    function save_expanded(expanded) {
        let obj = [];
        let json = "";
        $.each(expanded, function (key, value) {
            let item = {
                'id': $(value).data('id'),
                'value':value.innerHTML
            };

            obj.push(item);
        });

        json = JSON.stringify(obj);
        localStorage.setItem('main-menu-expanded', json);
    }

    function remove_expanded(id) {
        let expanded = localStorage.getItem('main-menu-expanded');
        let obj = "";
        try {
            obj = JSON.parse(expanded);
        } catch(e) {
            console.log('wrong json');
        }
        
        $.each(obj, function(key, value) {
            if (value.id == id)
                obj.splice(key, 1);
        });
        
        json = JSON.stringify(obj);
        localStorage.setItem('main-menu-expanded', json);
    }

    function add_cancel_tooltip(obj) {
        let box = obj.children('.cat-tree-row');
        obj.closest('.catalog-tree').find('.to-cancel').removeClass('to-cancel');
        box.addClass('to-cancel');
        return true;
    }

    function get_item_attr(item) {
        let prnt = item.closest('ul');
        let box = prnt.closest('li');
        let id = item.data('id');
        let parentid = box.data('id');
        let after = item.prev().data('id') || item.next().data('id');

        let collector = {
            'id': id,
            'parent': parentid,
            'after': after,
        };
        return collector;
    }

    function send_reorder_data(collector) {
        $.post('/struct/reorderpages', collector, function(data) {
            //console.log(data);
        });
    }

    function get_expanded(options) {
        /* initiate sortable */
        let expanded = localStorage.getItem('main-menu-expanded');

        if (typeof(expanded != 'undefined')) {
            try {
                let obj = JSON.parse(expanded);

                obj.forEach(function(data, i) {
                    $('.struct li[data-id=' + data.id + ']').addClass('expand');
                    $('.struct li[data-id=' + data.id + ']').html(data.value);
                });

                $('.struct ul').sortable(options);
            } catch(e) {
                console.log('Wrong json from local storage');
            }
        }
    }
});