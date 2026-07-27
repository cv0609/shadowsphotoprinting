window.MonthlyEditionForm = (function () {
    var orderedIds = [];
    var dragEl = null;
    var sectionDragEl = null;
    var sectionEditors = {};

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

    function sectionRows() {
        return Array.prototype.slice.call(document.querySelectorAll('#me-sections-list [data-section-row]'));
    }

    function reindexSections() {
        sectionRows().forEach(function (row, index) {
            row.querySelectorAll('[name^="sections["]').forEach(function (input) {
                input.name = input.name.replace(/sections\[(\d+|__INDEX__)\]/, 'sections[' + index + ']');
            });
            var sort = row.querySelector('[data-section-sort]');
            if (sort) sort.value = String(index + 1);
            var num = row.querySelector('.me-section-row__num');
            if (num) num.textContent = String(index + 1);
            var titleInput = row.querySelector('[data-section-title]');
            var label = row.querySelector('.me-section-row__label');
            if (label && titleInput) {
                label.textContent = titleInput.value.trim() || 'New section';
            }
        });
    }

    function destroySectionEditor(row) {
        var textarea = row.querySelector('[data-section-editor]');
        if (!textarea || !textarea.id) return;
        if (sectionEditors[textarea.id]) {
            try {
                sectionEditors[textarea.id].destroy();
            } catch (e) {}
            delete sectionEditors[textarea.id];
        }
    }

    function initSectionEditor(textarea) {
        if (!textarea || typeof ClassicEditor === 'undefined') return;
        if (!textarea.id) {
            textarea.id = 'me-section-editor-' + Date.now() + '-' + Math.floor(Math.random() * 10000);
        }
        if (sectionEditors[textarea.id] || textarea.dataset.ckReady === '1') return;
        textarea.dataset.ckReady = '1';
        ClassicEditor.create(textarea).then(function (editor) {
            sectionEditors[textarea.id] = editor;
        }).catch(console.error);
    }

    function initAllSectionEditors() {
        sectionRows().forEach(function (row) {
            var textarea = row.querySelector('[data-section-editor]');
            initSectionEditor(textarea);
        });
    }

    function moveSection(row, direction) {
        var list = document.getElementById('me-sections-list');
        if (!list || !row) return;
        if (direction < 0 && row.previousElementSibling) {
            list.insertBefore(row, row.previousElementSibling);
        } else if (direction > 0 && row.nextElementSibling) {
            list.insertBefore(row.nextElementSibling, row);
        }
        reindexSections();
    }

    function bindSectionRow(row) {
        row.addEventListener('dragstart', function (e) {
            if (e.target.closest('input, textarea, select, button, .ck')) {
                e.preventDefault();
                return;
            }
            sectionDragEl = row;
            row.classList.add('dragging');
        });
        row.addEventListener('dragend', function () {
            row.classList.remove('dragging');
            sectionDragEl = null;
            reindexSections();
        });
        row.addEventListener('dragover', function (e) {
            e.preventDefault();
            if (!sectionDragEl || sectionDragEl === row) return;
            var list = document.getElementById('me-sections-list');
            var rect = row.getBoundingClientRect();
            var after = (e.clientY - rect.top) > rect.height / 2;
            list.insertBefore(sectionDragEl, after ? row.nextSibling : row);
        });

        var titleInput = row.querySelector('[data-section-title]');
        if (titleInput) {
            titleInput.addEventListener('input', function () {
                var label = row.querySelector('.me-section-row__label');
                if (label) label.textContent = titleInput.value.trim() || 'New section';
            });
        }

        row.querySelectorAll('[data-section-move]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                moveSection(row, btn.getAttribute('data-section-move') === 'up' ? -1 : 1);
            });
        });

        var removeBtn = row.querySelector('[data-section-remove]');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                destroySectionEditor(row);
                row.remove();
                reindexSections();
            });
        }
    }

    function addSection() {
        var list = document.getElementById('me-sections-list');
        var template = document.getElementById('me-section-template');
        if (!list || !template) return;

        var html = template.innerHTML.replace(/__INDEX__/g, String(sectionRows().length));
        var wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        var row = wrap.firstElementChild;
        list.appendChild(row);
        bindSectionRow(row);
        reindexSections();
        initSectionEditor(row.querySelector('[data-section-editor]'));
    }

    function syncEditorsToTextareas() {
        Object.keys(sectionEditors).forEach(function (id) {
            try {
                if (sectionEditors[id] && typeof sectionEditors[id].updateSourceElement === 'function') {
                    sectionEditors[id].updateSourceElement();
                }
            } catch (e) {}
        });
    }

    function isBlankSectionRow(row) {
        var idInput = row.querySelector('input[name*="[id]"]');
        if (idInput && idInput.value) return false;

        var title = (row.querySelector('[data-section-title]') || {}).value || '';
        var caption = (row.querySelector('input[name*="[image_caption]"]') || {}).value || '';
        var fileInput = row.querySelector('input[type="file"][name*="[image]"]');
        var hasFile = fileInput && fileInput.files && fileInput.files.length;

        var textarea = row.querySelector('[data-section-editor]');
        var content = '';
        if (textarea && textarea.id && sectionEditors[textarea.id]) {
            content = sectionEditors[textarea.id].getData() || '';
        } else if (textarea) {
            content = textarea.value || '';
        }

        var text = (content + '').replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
        return !title.trim() && !text && !caption.trim() && !hasFile;
    }

    function prepareSectionsForSubmit() {
        syncEditorsToTextareas();
        sectionRows().forEach(function (row) {
            if (isBlankSectionRow(row)) {
                destroySectionEditor(row);
                row.remove();
            }
        });
        reindexSections();
        syncEditorsToTextareas();
    }

    function bindFormSubmit() {
        var form = document.querySelector('form.form-horizontal');
        if (!form || form.dataset.meSectionsBound === '1') return;
        form.dataset.meSectionsBound = '1';
        form.addEventListener('submit', function () {
            prepareSectionsForSubmit();
        });
    }

    function initSections() {
        var addBtn = document.getElementById('me-add-section');
        if (addBtn) {
            addBtn.addEventListener('click', addSection);
        }
        sectionRows().forEach(bindSectionRow);
        reindexSections();
        initAllSectionEditors();
        bindFormSubmit();
    }

    function init(initialIds) {
        orderedIds = (initialIds || []).map(function (id) { return parseInt(id, 10); }).filter(Boolean);

        document.querySelectorAll('.me-blog-check').forEach(function (checkbox) {
            var id = parseInt(checkbox.value, 10);
            checkbox.checked = orderedIds.indexOf(id) !== -1;
            checkbox.addEventListener('change', onCheckChange);
        });

        renderOrdered();
        initSections();
    }

    return { init: init };
})();
