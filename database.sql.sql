CREATE TABLE building (
    building_name VARCHAR(100) PRIMARY KEY NOT NULL
);

CREATE TABLE study_room (
    room_id INT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    room_capacity INT NOT NULL,
    room_open TIME,
    room_close TIME,
    room_occupied INT DEFAULT 0, -- 0 for not occupied, 1 for occupied
    room_equip ENUM ('whiteboard' , 'smartboard', 'both', 'none') default 'none',
    build_id VARCHAR(100),
    FOREIGN KEY (build_id) REFERENCES building(building_name)
);

CREATE TABLE student (
    s_id VARCHAR(7) PRIMARY KEY,
    s_name VARCHAR(100) NOT NULL
);

CREATE TABLE study_reservation (
    sr_id INT PRIMARY KEY AUTO_INCREMENT,
    sr_num INT,
    s_id VARCHAR(7),
    b_id VARCHAR(200),
    sr_start VARCHAR(200),
    sr_end VARCHAR(200),
    r_cap INT,
	sr_purpose VARCHAR (200),
  	sr_extra TEXT
);