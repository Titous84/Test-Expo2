import MDEditor from '@uiw/react-md-editor';
import rehypeSanitize from 'rehype-sanitize';
import React from 'react';

interface MDContentEditorProps{
    content:string;
    onChange:(content:string | undefined)=>void;
}

/**
 * Composant qui permet d'éditer du contenu markdown.
 * @author Mathieu Sévégny
 */
export default class MDContentEditor extends React.Component<MDContentEditorProps> {
    render(){
        return (
            <div data-color-mode="light">
                {<MDEditor
                    height={600} 
                    value={this.props.content} 
                    onChange={this.props.onChange}
                    previewOptions={{
                        rehypePlugins: [[rehypeSanitize]],
                      }}
                />}
            </div>
        );
    }
}