window.MonthlyEditionForm = (function () {
    var orderedIds = [];
    var dragEl = null;

    function titleFor(id) {
        var option = document.querySelector('.me-blog-option[data-blog-id="' + id + '"]');
        return option ? option.getAttribute('data-blog-title') : ('Blog #' + id);
    }

    function syncHiddenInputs() {
        var wrap = document.getElementById('me-blog-ids-inputs');
        if (!wrap) return;
        wrap.innerHTML = '';
        orderedIds.forEach(function (id) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'blog_ids[]';
            input.value = id;
            wrap.appendChild(input);
        });
    }

    function renderOrdered() {
        var list = document.getElementById('me-ordered-blogs');
        var empty = document.getElementById('me-ordered-empty');
        if (!list) return;

        list.innerHTML = '';
        orderedIds.forEach(function (id, index) {
            var li = document.createElement('li');
            li.draggable = true;
            li.dataset.id = String(id);
            li.innerHTML =
                '<span class="me-order-num">' + (index + 1) + '</span>' +
                '<span class="me-order-title"></span>' +
                '<span class="me-order-actions">' +
                    '<button type="button" data-move="up" title="Move up">↑</button>' +
                    '<button type="button" data-move="down" title="Move down">↓</button>' +
                '</span>';
            li.querySelector('.me-order-title').textContent = titleFor(id);
            list.appendChild(li);
        });

        if (empty) {
            empty.style.display = orderedIds.length ? 'none' : 'block';
        }
        syncHiddenInputs();
        bindDrag();
    }

    function moveId(id, direction) {
        var index = orderedIds.indexOf(id);
        if (index < 0) return;
        var target = index + direction;
        if (target < 0 || target >= orderedIds.length) return;
        var tmp = orderedIds[index];
        orderedIds[index] = orderedIds[target];
        orderedIds[target] = tmp;
        renderOrdered();
    }

    function onCheckChange(e) {
        var checkbox = e.target;
        if (!checkbox.classList.contains('me-blog-check')) return;
        var id = parseInt(checkbox.value, 10);
        if (checkbox.checked) {
            if (orderedIds.indexOf(id) === -1) {
                orderedIds.push(id);
            }
        } else {
            orderedIds = orderedIds.filter(function (x) { return x !== id; });
        }
        renderOrdered();
    }

    function bindDrag() {
        var list = document.getElementById('me-ordered-blogs');
        if (!list) return;

        Array.prototype.forEach.call(list.querySelectorAll('li'), function (li) {
            li.addEventListener('dragstart', function () {
                dragEl = li;
                li.classList.add('dragging');
            });
            li.addEventListener('dragend', function () {
                li.classList.remove('dragging');
                dragEl = null;
                orderedIds = Array.prototype.map.call(list.querySelectorAll('li'), function (node) {
                    return parseInt(node.dataset.id, 10);
                });
                renderOrdered();
            });
            li.addEventListener('dragover', function (e) {
                e.preventDefault();
                if (!dragEl || dragEl === li) return;
                var rect = li.getBoundingClientRect();
                var after = (e.clientY - rect.top) > rect.height / 2;
                list.insertBefore(dragEl, after ? li.nextSibling : li);
            });
            li.querySelectorAll('[data-move]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = parseInt(li.dataset.id, 10);
                    moveId(id, btn.getAttribute('data-move') === 'up' ? -1 : 1);
                });
            });
        });
    }

    function init(initialIds) {
        orderedIds = (initialIds || []).map(function (id) { return parseInt(id, 10); }).filter(Boolean);

        document.querySelectorAll('.me-blog-check').forEach(function (checkbox) {
            var id = parseInt(checkbox.value, 10);
            checkbox.checked = orderedIds.indexOf(id) !== -1;
            checkbox.addEventListener('change', onCheckChange);
        });

        renderOrdered();
    }

    return { init: init };
})();
