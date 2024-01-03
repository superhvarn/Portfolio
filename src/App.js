import React, { useEffect, useState, useMemo } from 'react';
import { useTable } from 'react-table';

async function fetchData() {
  try {
    const response = await fetch("http://localhost:8000/api/index", {
      method: 'GET',
      headers: {
        'User-Agent': 'PHP',
        'Accept': 'application/json',
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error fetching data:", error);
    throw error;
  }
}

function App() {
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchDataAndSetData = async () => {
      try {
        const fetchedData = await fetchData();
        setData(fetchedData);
      } catch (error) {
        setError(error);
      }
    };

    fetchDataAndSetData();
  }, []); // Empty dependency array ensures the effect runs only once, similar to componentDidMount

  const columns = useMemo(
    () => [
      { Header: "Name", accessor: "name" },
      { Header: "Description", accessor: "description" },
    ],
    []
  );

  const {
    getTableProps,
    getTableBodyProps,
    headerGroups,
    rows,
    prepareRow
  } = useTable({ columns, data });

  return (
    <div className="App">
      {error ? (
        <div>Error fetching data: {error.message}</div>
      ) : (
        <div>
          <header className="App-header">
            <h2>Hi, I'm Harish.</h2>
            <text>a full-stack developer</text>
            <div className="table-container">
              <table {...getTableProps()}>
                <thead>
                  {headerGroups.map((headerGroup) => (
                    <tr {...headerGroup.getHeaderGroupProps()}>
                      {headerGroup.headers.map((column) => (
                        <th {...column.getHeaderProps()}>{column.render("Header")}</th>
                      ))}
                    </tr>
                  ))}
                </thead>
                <tbody {...getTableBodyProps()}>
                  {rows.map((row) => {
                    prepareRow(row);
                    return (
                      <tr {...row.getRowProps()}>
                        {row.cells.map((cell) => (
                          <td {...cell.getCellProps()}>{cell.render("Cell")}</td>
                        ))}
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>
            <div>
              <div>
                <a href="https://github.com/superhvarn">
                  <button name="submit" type="submit" value="Github" className="button">Github</button>
                </a>
                <a href="mailto:harivarada1@gmail.com">
                  <button onClick={() => window.location = 'mailto:harivarada1@gmail.com'}>Contact Me</button>
                </a>
                <p id="saved"></p>
              </div>
            </div>
          </header>
        </div>
      )}
    </div>
  );
}

export default App;
