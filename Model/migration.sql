-- GlobalTicket Migration — run once in phpMyAdmin or MySQL CLI
USE globaltickets;

-- Extend events table
ALTER TABLE events
    ADD COLUMN IF NOT EXISTS discography_id INT,
    ADD COLUMN IF NOT EXISTS artist         VARCHAR(150),
    ADD COLUMN IF NOT EXISTS price          DECIMAL(10,2) DEFAULT 0.00,
    ADD COLUMN IF NOT EXISTS capacity       INT           DEFAULT 100,
    ADD COLUMN IF NOT EXISTS image          VARCHAR(255),
    ADD COLUMN IF NOT EXISTS latitude       DECIMAL(10,7),
    ADD COLUMN IF NOT EXISTS longitude      DECIMAL(10,7);

-- Foreign key (safe to skip if already added)
ALTER TABLE events
    ADD CONSTRAINT fk_event_disco
    FOREIGN KEY (discography_id) REFERENCES discographies(id) ON DELETE SET NULL;

-- Reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    user_id     INT NOT NULL,
    event_id    INT NOT NULL,
    quantity    INT DEFAULT 1,
    reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reservation (user_id, event_id)
);
