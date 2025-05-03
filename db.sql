/* 2025-02-14 17:34:40 [3 ms] */ 
use privnote;
/* 2025-02-14 19:34:59 [26 ms] */ 
create table messages (
    id serial primary key,
    message text not null,
    url text not null,
    created_at timestamp not null default now(),
    secure_key text not null,
    iv text not null
);
/* 2025-02-14 20:14:58 [14 ms] */ 
DELETE FROM `messages` WHERE `id` IN (1,2,3921425602804,7165985571672,7180673807513);
/* 2025-02-14 20:45:32 [14 ms] */ 
DELETE FROM `messages` WHERE `id` IN (7293548787082,7710283142398);
