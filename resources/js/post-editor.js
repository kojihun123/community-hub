import {
    AutoImage,
    SimpleUploadAdapter,
    BlockQuote,
    Bold,
    ClassicEditor,
    Essentials,
    Heading,
    Image,
    ImageCaption,
    ImageInsert,
    ImageResize,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Italic,
    Link,
    List,
    Paragraph,
    PictureEditing,
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';

const initPostEditor = () => {
    const editorElement = document.querySelector('#editor');
    const contentInput = document.querySelector('#content');
    const form = document.querySelector('form[data-post-editor]');

    if (!editorElement || !contentInput || !form) {
        return;
    }

    ClassicEditor.create(editorElement, {
        licenseKey: 'GPL',
        plugins: [
            AutoImage,
            SimpleUploadAdapter,
            Essentials,
            Paragraph,
            Heading,
            Bold,
            Italic,
            Link,
            List,
            BlockQuote,
            Image,
            ImageCaption,
            ImageInsert,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            ImageUpload,
            PictureEditing,
        ],
        toolbar: [
            'undo',
            'redo',
            '|',
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            '|',
            'insertImage',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'blockQuote',
        ],
        image: {
            toolbar: [
                'toggleImageCaption',
                'imageTextAlternative',
                '|',
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side',
                '|',
                'resizeImage',
            ],
        },
        initialData: contentInput.value || '',
        placeholder: '내용을 입력해주세요!',
        simpleUpload: {
            uploadUrl: '/uploads/posts/images',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content'),
            },
        },
    })
        .then((editor) => {
            const syncEditorContent = () => {
                contentInput.value = editor.getData();
            };

            syncEditorContent();

            editor.model.document.on('change:data', () => {
                syncEditorContent();
            });

            form.addEventListener('submit', () => {
                syncEditorContent();
            });

            window.postEditor = editor;
        })
        .catch((error) => {
            console.error('CKEditor 초기화 에러:', error);
        });
};

document.addEventListener('DOMContentLoaded', initPostEditor);
