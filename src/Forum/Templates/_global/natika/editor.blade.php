{{-- Part of natika project. --}}

<?php
\Natika\Script\EditorScript::codeMirror('editor', '#input-body');
?>

<div id="editor" class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            Editor
        </div>
        <div class="pull-right">

        </div>
    </div>
    <div class="panel-body">
        @if (empty($reply))
        <div class="form-group">
            <label for="input-title" class="sr-only">Title</label>
            <input type="text" class="form-control input-lg" id="input-title" name="item[title]" placeholder="Title" value="{{ $post->title }}">
        </div>
        @endif

            <div class="toolbar">
                <div class="btn-group">
                    <button id="button-h1" class="btn btn-default btn-sm">H1</button>
                    <button id="button-h2" class="btn btn-default btn-sm">H2</button>
                    <button id="button-h3" class="btn btn-default btn-sm">H3</button>
                    <button id="button-h4" class="btn btn-default btn-sm">H4</button>
                    <button id="button-h5" class="btn btn-default btn-sm">H5</button>
                </div>

                <div class="btn-group">
                    <button id="button-strong" class="btn btn-default btn-sm">
                        <span class="fa fa-bold"></span>
                    </button>
                    <button id="button-italic" class="btn btn-default btn-sm">
                        <span class="fa fa-italic"></span>
                    </button>
                </div>

                <div class="btn-group">
                    <button id="button-ul" class="btn btn-default btn-sm">
                        <span class="fa fa-list-ul"></span>
                    </button>
                    <button id="button-ol" class="btn btn-default btn-sm">
                        <span class="fa fa-list-ol"></span>
                    </button>
                </div>

                <div class="btn-group">
                    <button id="button-img" class="btn btn-default btn-sm">
                        <span class="fa fa-picture-o"></span>
                    </button>
                    <button id="button-link" class="btn btn-default btn-sm">
                        <span class="fa fa-link"></span>
                    </button>
                </div>

                <div class="btn-group">
                    <button id="button-quote" class="btn btn-default btn-sm">
                        <span class="fa fa-quote-right"></span>
                    </button>
                    <button id="button-codeblock" class="btn btn-default btn-sm">
                        <span class="fa fa-file-code-o"></span>
                    </button>
                    <button id="button-code" class="btn btn-default btn-sm">
                        <span class="fa fa-code"></span>
                    </button>
                </div>

                {{--<div class="btn-group">--}}
                    {{--<button id="button-preview" class="btn btn-success btn-sm">Preview</button>--}}
                {{--</div>--}}
            </div>

        <div class="form-group">
            <label for="input-body" class="sr-only">Body</label>
            <textarea class="form-control input-lg" id="input-body" name="item[body]" placeholder="Your content here...">{{ $post->body }}</textarea>
        </div>

        @if (!empty($reply))
        <div class="post-buttons">
            <button class="btn btn-success btn-lg pull-right">Reply</button>
        </div>
        @endif
    </div>
</div>

@section('script')
@parent
<script src="{{ $uri['media.path'] }}js/fongshen/editor/codemirror-adapter.js"></script>
<script src="{{ $uri['media.path'] }}js/fongshen/fongshen.js"></script>
<script>
jQuery(document).ready(function($)
{
    var editor = $('#input-body');

    var options = {
        id: 'editor',
        namespace: 'natika'
        // previewAjaxPath: '',
        // previewContainer: '#preview-container'
        // buttons: FongshenMarkdownButtons
    };

    var Fongshen = editor.fongshen(new CodemirrorAdapter(window.codeMirror['editor']), options);

    // Buttons
    Fongshen.registerButton($('#button-h1'), {
        name:'Heading 1',
        key:"1",
        openWith:'# ',
        placeHolder:'Title'
    });

    Fongshen.registerButton($('#button-h2'), {
        name:'Heading 2',
        key:"2",
        openWith:'## ',
        placeHolder:'Title'
    });

    Fongshen.registerButton($('#button-h3'), {
        name:'Heading 3',
        key:"3",
        openWith:'### ',
        placeHolder:'Title'
    });

    Fongshen.registerButton($('#button-h4'), {
        name:'Heading 4',
        key:"4",
        openWith:'#### ',
        placeHolder:'Title'
    });

    Fongshen.registerButton($('#button-h5'), {
        name:'Heading 5',
        key:"5",
        openWith:'##### ',
        placeHolder:'Title'
    });

    Fongshen.registerButton($('#button-h6'), {
        name:'Heading 6',
        key:"6",
        openWith:'###### ',
        placeHolder:'Title'
    });

    Fongshen.registerButton($('#button-strong'), {
            name:'Bold',
            key:"B",
            openWith:'**',
            closeWith:'**'}
    );

    Fongshen.registerButton($('#button-italic'), {
        name:'Italic',
        key:"I",
        openWith:'_',
        closeWith:'_'
    });

    Fongshen.registerButton($('#button-ul'), {
        name:'Bulleted List',
        openWith:'- ' ,
        multiline: true
    });

    Fongshen.registerButton($('#button-ol'), {
        name:'Numeric List',
        openWith: function(fongshen)
        {
            return fongshen.line + '. ';
        },
        multiline: true
    });

    Fongshen.registerButton($('#button-img'), {
        name:'Picture',
        key:"P",
        replaceWith: function(fongshen)
        {
            var value = '![' + fongshen.ask('Alternative text') + '](' + fongshen.ask('Url', 'http://');

            var title = fongshen.ask('Title');

            if (title !== '')
            {
                value += ' "' + title + '"';
            }

            value += ')';

            return value;
        }
    });

    Fongshen.registerButton($('#button-link'), {
        name:'Link',
        key:"L",
        openWith:'[',
        closeWith: function(fongshen)
        {
            return '](' + fongshen.ask('Url', 'http://') + ')';
        },
        placeHolder:'Click here to link...'
    });

    Fongshen.registerButton($('#button-quote'), {
        name:'Quotes',
        openWith:'> ',
        multiline: true
    });

    Fongshen.registerButton($('#button-codeblock'), {
        name:'Code Block / Code',
        openWith: function(fongshen)
        {
            return '``` ' + fongshen.ask('Language') + '\n';
        },
        closeWith:'\n```',
        afterInsert: function(fongshen)
        {
            fongshen.getEditor().moveCursor(-1, 0);
        }
    });

    Fongshen.registerButton($('#button-code'), {
        name:'Code Inline',
        openWith:'`',
        closeWith:'`',
        multiline: true,
        className: "code-inline"
    });

    Fongshen.registerButton($('#button-preview'), {
        name:'Preview',
        call:'createPreview',
        className:"preview"
    });
});
</script>

@stop