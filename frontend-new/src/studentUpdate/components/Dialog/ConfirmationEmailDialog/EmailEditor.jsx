import React from 'react';
import RichTextEditor from 'react-rte';

const toolbarConfig = {
  display: ['INLINE_STYLE_BUTTONS', 'BLOCK_TYPE_BUTTONS', 'LINK_BUTTONS', 'BLOCK_TYPE_DROPDOWN', 'HISTORY_BUTTONS'],
  INLINE_STYLE_BUTTONS: [
    { label: 'Bold', style: 'BOLD' },
    { label: 'Italic', style: 'ITALIC' },
    { label: 'Underline', style: 'UNDERLINE' }
  ],
  BLOCK_TYPE_DROPDOWN: [
    { label: 'Normal', style: 'unstyled' },
    { label: 'Heading Large', style: 'header-one' },
    { label: 'Heading Medium', style: 'header-two' },
    { label: 'Heading Small', style: 'header-three' }
  ],
  BLOCK_TYPE_BUTTONS: [{ label: 'UL', style: 'unordered-list-item' }, { label: 'OL', style: 'ordered-list-item' }]
};

function EmailEditor(props) {
  return (
    <div>
      <RichTextEditor
        value={props.editorState}
        onChange={editorState =>props.setEditorState(editorState)}
        toolbarConfig={toolbarConfig}
      />
    </div>
  );
}

export default EmailEditor;
