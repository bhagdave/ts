# Multi User Agency
Thought it best to document a few decisions made while moving agencies to be able to have multipe users.

So landlords and properties (tables) both had a link field to agent_id. Which unsuprisingly linked to the agent table.  Now because we are keeping agents who are **always** linked to an agency I have changed that field so that it now points to an agency record, which is a new table.

Agents now have a new field, agency_id, which points to the new table agency.

Hope that makes sense.


