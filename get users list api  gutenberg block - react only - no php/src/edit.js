
import { __ } from '@wordpress/i18n';

import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

import React, { useEffect, useState } from "react"

export default function Edit(props) {



	const [users, setUsers] = useState([])

	const fetchData = async () => {
		const response = await fetch("https://jsonplaceholder.typicode.com/users")
		const data = await response.json()
		setUsers(data)
		props.setAttributes({ weatherApiData: data })
		console.log("data: ", data)
	}

	useEffect(() => {
		fetchData()
	}, [])


	return (<>
		<div {...useBlockProps()}>


			<p >
				{__('Show Weather – hello from the editor!', 'show-weather')}
			</p>

			<div>
				{users.length > 0 && (
					<ul>
						{users.map(user => (
							<li key={user.id}>{user.name}</li>
						))}
					</ul>
				)}
			</div>

		</div>

	</>);
}




/* 
const AsyncAwait = () => {
	const [users, setUsers] = useState([])
  
	const fetchData = async () => {
	  const response = await fetch("https://jsonplaceholder.typicode.com/users")
	  const data = await response.json()
	  setUsers(data)
	}
  
	useEffect(() => {
	  fetchData()
	}, [])
  
	return (
	  <div>
		{users.length > 0 && (
		  <ul>
			{users.map(user => (
			  <li key={user.id}>{user.name}</li>
			))}
		  </ul>
		)}
	  </div>
	)
  }
  
  export default AsyncAwait */
