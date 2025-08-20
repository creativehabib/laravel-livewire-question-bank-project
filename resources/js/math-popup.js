import Swal from 'sweetalert2';
import katex from 'katex';
/**
 * Popup open করে LaTeX insert/update করার জন্য
 */
export function openMathPopup(editorKey, existingLatex = '', formulaEl = null) {
    Swal.fire({
        title: existingLatex ? 'Update Formula' : 'Insert Formula',
        html: `
            <div id="laraPreview" class="text-left">
                <label class="block text-sm font-medium text-gray-700" for="latexInput">Write your LaTeX here</label>
                <textarea id="latexInput"
                    class="w-full border rounded p-3 text-sm mt-0 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="x = \\\\frac{-b \\\\pm \\\\sqrt{b^2-4ac}}{2a}">${existingLatex}</textarea>

                <div class="flex justify-end -mt-1">
                    <a class="text-xs text-blue-500 underline"
                       href="https://en.wikibooks.org/wiki/LaTeX/Mathematics"
                       target="_blank">
                       TeX documentation
                    </a>
                </div>

                <label class="block text-sm font-medium text-gray-700">Preview</label>
                <div id="latexPreview" class="border rounded bg-gray-50 p-4 min-h-[60px] text-gray-400 mt-1 flex items-center justify-center text-center">
                    Preview will appear here...
                </div>
            </div>
        `,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: existingLatex ? 'Update' : 'Insert',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'rounded-md shadow bg-white cursor-move',
            title: 'text-lg font-semibold text-left border-b pb-2 pt-2',
            confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded font-medium',
            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-1 rounded font-medium'
        },
        didOpen: (popup) => {
            // ✅ latex preview
            const input = document.getElementById('latexInput');
            const preview = document.getElementById('latexPreview');
            if (existingLatex) katex.render(existingLatex, preview, { throwOnError: false });

            input.addEventListener('input', () => {
                try {
                    katex.render(input.value, preview, { throwOnError: false });
                } catch {
                    preview.innerHTML = '<span class="text-red-500">Invalid LaTeX</span>';
                }
            });

            // ✅ title padding override
            const titleEl = popup.querySelector('.swal2-title');
            if (titleEl) {
                titleEl.style.paddingTop = '0.5rem';   // pt-2
                titleEl.style.paddingBottom = '0.5rem'; // pb-2
            }

            // ✅ draggable feature (পুরো popup)
            let isDown = false, offset = [0, 0];
            popup.addEventListener('mousedown', (e) => {
                isDown = true;
                if (popup.style.transform !== 'none') {
                    const rect = popup.getBoundingClientRect();
                    popup.style.position = 'fixed';
                    popup.style.margin = 0;
                    popup.style.transform = 'none';
                    popup.style.left = rect.left + 'px';
                    popup.style.top = rect.top + 'px';
                }
                offset = [popup.offsetLeft - e.clientX, popup.offsetTop - e.clientY];
            });

            document.addEventListener('mouseup', () => isDown = false);
            document.addEventListener('mousemove', (e) => {
                if (isDown) {
                    popup.style.left = (e.clientX + offset[0]) + 'px';
                    popup.style.top  = (e.clientY + offset[1]) + 'px';
                }
            });
        },
        preConfirm: () => document.getElementById('latexInput').value
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const editor = quillEditors[editorKey];
            if (!editor) return;

            if (formulaEl) {
                formulaEl.setAttribute('data-value', result.value);
                formulaEl.innerHTML = katex.renderToString(result.value, { throwOnError: false });
            } else {
                const index = editor.getSelection(true).index;
                editor.insertEmbed(index, 'formula', result.value, Quill.sources.USER);
                editor.setSelection(index + 1);
            }
        }
    });
}

/**
 * কোন editor থেকে formula ক্লিক হয়েছে সেটা বের করে
 */
export function findEditorKey(node) {
    for (const key in quillEditors) {
        if (quillEditors[key].root.contains(node)) {
            return key;
        }
    }
    return null;
}

/**
 * Formula element এ ক্লিক করলে popup খোলে
 */
export function attachFormulaClickHandler() {
    document.addEventListener('click', function (e) {
        const formulaEl = e.target.closest('.ql-formula');
        if (formulaEl) {
            const latex = formulaEl.getAttribute('data-value');
            const editorKey = findEditorKey(formulaEl);
            if (editorKey) {
                openMathPopup(editorKey, latex, formulaEl);
            }
        }
    });
}
