# Email campain program

EmailMsg
  id
  templateName
  subject
  msgBody
  status - "Active" / "Inactive"
  dateCreated
  dateUpdated
Contact
  id
  name
  email
  phone
  company
  status - "Active" / "Inactive"
  dateCreated
  dateUpdated
EmailMsgContact
  id
  emailMsgId
  contactId
EmailMsgContactSchedule
  id
  emailMsgContactId
  scheduleId
Schedule
  id
  emailMsgId
  title
  curlString
  status - "Active" / "Inactive"
  dateCreated
  dateUpdated
