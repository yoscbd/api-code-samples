import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';
exportdefault
function Edit() {

	return (
		<p {...useBlockProps()}>
			{__('Starter – hello from the editor!', 'starter')}
		</p>
	);
}
