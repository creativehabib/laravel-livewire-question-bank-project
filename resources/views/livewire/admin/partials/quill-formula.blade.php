@push('scripts')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let quillEditors = {};

        document.addEventListener('livewire:navigated', () => {
            const toolbarOptions = [
                ['bold', 'italic'],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['formula'], ['customMath'], ['clean']
            ];

            // Main editor
            let mainEditor = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    formula: true,
                    toolbar: {
                        container: toolbarOptions,
                        handlers: {
                            customMath: function () { openMathPopup('title'); }
                        }
                    }
                }
            });
            quillEditors['title'] = mainEditor;
            mainEditor.on('text-change', function () {
            @this.set('title', mainEditor.root.innerHTML);
            });

            // Option editors
            document.querySelectorAll('[id^="opt_editor_"]').forEach(el => {
                let index = el.id.replace('opt_editor_', '');
                let optEditor = new Quill(`#${el.id}`, {
                    theme: 'snow',
                    modules: {
                        formula: true,
                        toolbar: {
                            container: toolbarOptions,
                            handlers: {
                                customMath: function () { openMathPopup(`option_${index}`); }
                            }
                        }
                    }
                });
                quillEditors[`option_${index}`] = optEditor;
                optEditor.on('text-change', function () {
                @this.set(`options.${index}.option_text`, optEditor.root.innerHTML);
                });
            });

            document.querySelectorAll('.ql-customMath').forEach(btn => {
                btn.innerHTML = '∑';
            });
        });

        function openMathPopup(editorKey) {
            Swal.fire({
                title: 'Insert Formula (TeX)',
                html: `<textarea id="latexInput" class="swal2-textarea"></textarea>
                   <div id="latexPreview" style="min-height:50px; margin-top:10px;"></div>`,
                showCancelButton: true,
                confirmButtonText: 'Insert',
                didOpen: () => {
                    const input = document.getElementById('latexInput');
                    const preview = document.getElementById('latexPreview');
                    input.addEventListener('input', () => {
                        try { katex.render(input.value, preview, { throwOnError: false }); }
                        catch (err) { preview.innerHTML = '<span class="text-red-500">Invalid LaTeX</span>'; }
                    });
                },
                preConfirm: () => document.getElementById('latexInput').value
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const editor = quillEditors[editorKey];
                    const index = editor.getSelection(true).index;
                    editor.insertEmbed(index, 'formula', result.value, Quill.sources.USER);
                    editor.setSelection(index + 1);
                }
            });
        }
    </script>
@endpush
