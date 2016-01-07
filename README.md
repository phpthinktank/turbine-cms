# turbine-cms
Powerfull cms

## Configuration

### Nodes

Nodes are the heart of Turbine CMS. Nodes are necessary to provide multi-domain-support and also environment control. Within each node you could define a specific config location.

#### Defaults

The default node is defined within `/app/res/config/nodes.json` and is defined as follows.

```json
{
  "default": {
    "nodes": {
      "default": {
        "type": "url_path",
        "match": "^.+$",
        "priority": "last",
        "description": "Is valid for every given domain and path and act as wildcard. This will load at the end when nothing has been matched",
        "config": "<root_path>/<node_id>/<environment>"
      }
    }
  }
}
```

#### Architecture

```json
{
  "<environment>": {
    "nodes": {
      "<node_id>": {
        "type": "<url_path, url_host, url_query, all>",
        "match": "<regex>",
        "priority": "<first, any number, last",
        "description": "",
        "config": "<root_path>/<node_id>/<environment>"
      }
    }
  }
}
```

### 
