/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import AWN from 'awesome-notifications'
import TextareaMarkdown from 'textarea-markdown'
import MarkdownIt from 'markdown-it'
import hljs from 'highlight.js';
import CodeMirror from 'codemirror';

let globalNotificationsConfig = {
    position: 'top-right'
}
let Notifier = new AWN(globalNotificationsConfig)
let md = new MarkdownIt()

$(document).ready(function () {
    $('.ajax-data').each(function (idx, elt) {
        loadAjaxData(elt)
    })
})

function init() {
    initAjaxSaves()
    initAjaxSavesFields()
    initAjaxDeletes()

    initMarkdowns()
    initMarkdownIt()
    initHighlights()

    $('.ui.dropdown').dropdown({ fullTextSearch: true })
    $('.ui.radio.checkbox').checkbox()
    $('.ui.toggle.checkbox').checkbox()
    // $('.textarea-editor').each(function (idx, elt) {
    //     initCodeMirrorEditor($('.ui.modal form').data('codemirror-id') + '_' + $(elt).attr('id') + '_' + idx, elt)
    // })
}

function loadAjaxData(elt) {
    console.log('Load ajax data on ' + $(elt).attr('id'))
    let url = $(elt).data('url'), _this = elt

    if (url === undefined || url === '') {
        console.warn('Missing data-url on ' + id)
        return
    }

    $.ajax({
        type: 'GET',
        url: url,
        success: function (response) {
            $(_this).html(response)
            init()
        },
        error: function (response) {
            $(_this).html(response)
        },
    });
}

function initMarkdowns() {
    $('.markdown').each(function (idx, elt) {
        if ($(elt).data('initialized') === undefined) {
            new TextareaMarkdown(elt)
            $(elt).data('initialized', true)
            console.log('Textarea-markdown applied on ' + $(elt).attr('id'))
        }
    })
}

function initMarkdownIt() {
    $('.markdown-it').each(function (idx, elt) {
        if ($(elt).data('initialized') === undefined) {
            let data = $(elt).html()
            $(elt).html(md.render(data))
            $(elt).data('initialized', true)
            console.log('Markdown-it applied on ' + $(elt).attr('id'))
        }
    })
}

function initHighlights() {
    hljs.highlightAll();
}

function initAjaxSaves() {
    $('.ajax-save').each(function (idx, elt) {
        let id = $(elt).attr('id'),
            title = $(elt).data('title'),
            refreshContainer = $(elt).data('refresh-container'),
            refreshUrl = $(elt).data('refresh-url'),
            refreshMode = $(elt).data('refresh-mode'),
            dataReload = $(elt).data('reload')

        if (title === undefined || title === '') {
            console.warn('Missing data-title on ' + id)
            title = 'Missing title'
        }

        $(elt).unbind('click')
        $(elt).on('click', function () {
            let url = $(elt).data('url')

            if (url === undefined || url === '') {
                console.warn('Missing data-url on ' + id)
                return
            }

            $.ajax({
                url: url,
                success: function (response) {
                    initModal(title, url, response, refreshContainer, refreshUrl, refreshMode, dataReload)
                },
                error: function () {
                    console.log('Load failed on url ' + url)
                }
            })
        })
    })
}

function initAjaxDeletes() {
    $('.ajax-delete').each(function (idx, elt) {
        $(elt).unbind('click')
        $(elt).on('click', function () {
            let url = $(elt).data('url'),
                refreshContainer = $(elt).data('refresh-container'),
                refreshUrl = $(elt).data('refresh-url'),
                refreshMode  = $(elt).data('refresh-mode'),
                dataReload = $(elt).data('reload')

            if (url === undefined || url === '') {
                console.warn('Missing data-url on ' + id)
                return
            }

            $.ajax({
                url: url,
                success: function (response) {
                    if (refreshContainer !== undefined && refreshUrl !== undefined) {
                        refresh(refreshContainer, refreshUrl, refreshMode)
                    }

                    if (dataReload !== undefined) {
                        $(dataReload).each(function (idx, elt) {
                            loadAjaxData(elt)
                        })
                    }
                },
                error: function () {
                    console.log('Load failed on url ' + url)
                }
            })
        })
    })
}

function initModal(title, url, response, refreshContainer, refreshUrl, refreshMode, dataReload) {
    $('.ui.modal .header').html(title)
    $('.ui.modal .content').html(response)
    $('.ui.modal .dropdown').dropdown({ fullTextSearch: true })
    $('.ui.modal .radio').checkbox()
    $('.ui.modal .checkbox').checkbox()
    $('.ui.modal').modal('close').modal({
        closable  : false,
        onVisible: function () {
            $('.ui.modal form .textarea-editor').each(function (idx, elt) {
                initCodeMirrorEditor($('.ui.modal form').data('codemirror-id') + '_' + $(this).attr('id') + '_' + idx, elt)
            })
        },
        onHide: function () {
            $('.ui.modal form .textarea-editor').each(function (idx, elt) {
                let id = 'editor_' + $('.ui.modal form').data('codemirror-id') + '_' + $(this).attr('id') + '_' + idx
                destroyCodeMirrorInstance(id)
            })
        },
        onApprove: function () {
            let data = $('.ui.modal form').serialize()

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function (response, type, request) {
                    $('.ui.modal form .textarea-editor').each(function (idx, elt) {
                        let id = 'editor_' + $('.ui.modal form').data('codemirror-id') + '_' + $(this).attr('id') + '_' + idx
                        destroyCodeMirrorInstance(id)
                    })
                    if (request.status === 201) {
                        Notifier.success()
                        if (refreshContainer !== undefined && refreshUrl !== undefined) {
                            refresh(refreshContainer, refreshUrl, refreshMode)
                        }

                        if (dataReload !== undefined) {
                            $(dataReload).each(function (idx, elt) {
                                loadAjaxData(elt)
                            })
                        }
                        $('.ui.modal').modal('hide')
                    } else {
                        initModal(title, url, response)
                    }
                },
                error: function (data, type, response) {
                    Notifier.alert()
                    initModal(title, url, response)
                },
            });

            return false
        }
    }).modal('show')
}

function initAjaxSavesFields() {
    $('.ajax-save-field').each(function (idx, elt) {
        $(elt).unbind('change')
        $(elt).on('change', function (e) {
            let url = $(this).data('url'),
                refreshContainer = $(this).data('refresh-container'),
                refreshUrl = $(this).data('refresh-url'),
                refreshMode = $(this).data('refresh-mode'),
                dataReload = $(this).data('reload'),
                value = $(this).val()

            if (url === undefined || url === '') {
                console.warn('Missing data-url on ' + $(e.target).attr('id'))
                return
            }

            $.ajax({
                method: 'POST',
                dataType: 'json',
                url: url,
                data: {'value': value},
                success: function (response) {
                    Notifier.success(response)
                    if (refreshContainer !== undefined && refreshUrl !== undefined) {
                        refresh(refreshContainer, refreshUrl, refreshMode)
                    }

                    if (dataReload !== undefined) {
                        $(dataReload).each(function (idx, elt) {
                            loadAjaxData(elt)
                        })
                    }
                },
                error: function (response) {
                    if (response.status === 500) {
                        Notifier.alert(response.responseJSON.detail)
                    } else {
                        Notifier.alert(response.responseJSON)
                    }
                }
            })
        })
    })
}

function refresh(refreshContainer, refreshUrl, refreshMode) {
    if (refreshContainer !== undefined && refreshUrl !== undefined) {
        let refreshData = null
        if (refreshMode === undefined) {
            refreshMode = 'replace'
        }

        $.ajax({
            type: 'POST',
            url: refreshUrl,
            success: function (response) {
                if (refreshMode === 'replace') {
                    $(refreshContainer).replaceWith(response)
                } else if (refreshMode === 'populate') {
                    $(refreshContainer).html(response)
                }
                init()
            },
            error: function () {
                refreshData = 'Failed to refresh...'
                $(refreshContainer).html(refreshData)
            }
        })
    }
}

function initCodeMirrorEditor(id, elt) {
    if (id === undefined) {
        console.error('Cannot init CodeMirror instance, missing id', elt)
        return
    }

    if (window['editor_' + id] === undefined) {
        let editor = CodeMirror.fromTextArea(elt, {
            lineNumbers: true,
            lineWrapping: true,
        });
        editor.setValue($(elt).text())
        editor.on('change', function(e, change){
            e.save()
        })
        editor.refresh();
        window['editor_' + id] = editor;
        console.warn('editor_' + id + ' created')
    }
}

function destroyCodeMirrorInstance(id) {
    if (window[id] !== undefined) {
        console.warn('Destroying instance ' + id)
        window[id] = undefined
    }
}
