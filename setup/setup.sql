CREATE TABLE IF NOT EXISTS Users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL,
    role TEXT NOT NULL CHECK(role IN ('admin', 'user')),
    approved BOOLEAN NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS Pharmacies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    address TEXT NOT NULL,
    phone TEXT NOT NULL,
    email TEXT NOT NULL,
    owner_id INTEGER NOT NULL,
    approved BOOLEAN NOT NULL DEFAULT 0,
    FOREIGN KEY(owner_id) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS PharmacyMeta (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pharmacy_id INTEGER NOT NULL,
    metakey TEXT NOT NULL,
    subject TEXT,
    value TEXT,
    FOREIGN KEY(pharmacy_id) REFERENCES Pharmacies(id)
);

CREATE TABLE IF NOT EXISTS MetaKeys (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    metakey TEXT NOT NULL UNIQUE,
    description TEXT
);
