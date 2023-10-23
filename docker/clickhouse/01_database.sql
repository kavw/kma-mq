
CREATE TABLE IF NOT EXISTS links (
  id UInt64,
  url String,
  content_length UInt32,
  sent_at DateTime64
)
ENGINE = MySQL('{{host}}','{{db}}','links','{{user}}','{{pass}}')
