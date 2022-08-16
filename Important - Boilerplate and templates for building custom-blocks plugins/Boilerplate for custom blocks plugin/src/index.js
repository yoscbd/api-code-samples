import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import save from './save';

registerBlockType('ybd-blocks/my-block', {
	edit: Edit,
	save,
});
