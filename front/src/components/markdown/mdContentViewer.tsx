import React from 'react';
import rehypeSanitize from 'rehype-sanitize';
import MDEditor from '@uiw/react-md-editor';

interface MDContentViewerProps{
    content:string;
}

/**
 * Composant qui permet de visualiser du contenu markdown.
 * @author Mathieu Sévégny
 */
export default class MDContentViewer extends React.Component<MDContentViewerProps> {
    render(){
        return (
            <div data-color-mode="light">
                {<MDEditor.Markdown
                    style={{ padding: 15 }}
                    source={this.props.content}
                    rehypePlugins={[[rehypeSanitize]]}
                />}
            </div>
        );
    }
}