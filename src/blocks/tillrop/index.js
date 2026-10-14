import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { Text, register } from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const p = { attributes, setAttributes };
	const blockProps = useBlockProps( { className: 'shout-box' } );
	const inner = useInnerBlocksProps(
		{ className: 'shouts' },
		{
			allowedBlocks: [ 'studentspex/shout' ],
			orientation: 'horizontal',
			template: [
				[
					'studentspex/shout',
					{
						label: 'Omstart!',
						icon: '🔁',
						result: 'Scenen spelas en gång till – fast lite annorlunda.',
					},
				],
			],
		}
	);
	return (
		<div { ...blockProps }>
			<Text { ...p } attr="title" tag="h3" plain />
			<Text { ...p } attr="intro" />
			<div { ...inner } />
			<div className="shout-stage">
				<span className="shout-stage__icon" aria-hidden="true">
					🎭
				</span>
				<Text
					{ ...p }
					attr="placeholder"
					className="shout-stage__text"
					plain
				/>
			</div>
		</div>
	);
}

register( metadata, Edit, { inner: true } );
