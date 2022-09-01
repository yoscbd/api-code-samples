
import { useBlockProps } from '@wordpress/block-editor';
import React, { useEffect, useState } from "react"

export default function save(props) {
	const blockProps = useBlockProps.save();
	const usersList = props.attributes.weatherApiData
	console.log("users from save: ", usersList)
	return (
		<>
			<div {...blockProps}>
				<p >
					Show Weather – hello from the editor!
				</p>

				<div>
					{usersList.length > 0 && (
						<ul>
							{usersList.map(user => (
								<li key={user.id}>{user.name}</li>
							))}
						</ul>
					)}
				</div>
			</div>

		</>);
}
