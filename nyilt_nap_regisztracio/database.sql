CREATE DATABASE IF NOT EXISTS nyilt_nap CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE nyilt_nap;

CREATE TABLE IF NOT EXISTS trades (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL UNIQUE,
  active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  previous_school VARCHAR(200) NOT NULL,
  residence ENUM('bejárós','koleszos') NOT NULL,
  is_graduating TINYINT(1) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_registrations_graduating (is_graduating),
  INDEX idx_registrations_residence (residence),
  INDEX idx_registrations_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registration_trades (
  registration_id BIGINT UNSIGNED NOT NULL,
  trade_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (registration_id, trade_id),
  CONSTRAINT fk_rt_registration FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
  CONSTRAINT fk_rt_trade FOREIGN KEY (trade_id) REFERENCES trades(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- MINTA szakmák: ezeket cseréld ki az iskola tényleges képzéseire!
INSERT IGNORE INTO trades (name, sort_order) VALUES
('Szoftverfejlesztő és -tesztelő', 1),
('Informatikai rendszer- és alkalmazás-üzemeltető technikus', 2),
('Gépjármű-mechatronikai technikus', 3),
('Pénzügyi-számviteli ügyintéző', 4),
('Kereskedelmi értékesítő', 5),
('Turizmus-vendéglátás', 6);
