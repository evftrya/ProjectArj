INSERT INTO `users` (`id_User`,`namaUser`, `emailUser`, `passwordUser`,`role`) VALUES
  (1,'Admin 1','a@a','123','Admin'),
  (2,'User 1','q@q','123','User'),
  (3,'User 2','w@w','123','User')
;

INSERT INTO `ref_category_products` (`category_name`, `created_at`, `updated_at`) VALUES
  ('Bass', NULL, NULL),
  ('Guitar', NULL, NULL)
;

INSERT INTO `ref_area_category_parts` (`Area`, `created_at`, `updated_at`) VALUES
  ('Body', NULL, NULL),
  ('General', NULL, NULL),
  ('Parts', NULL, NULL),
  ('Neck', NULL, NULL)
;

INSERT INTO `ref_category_parts` (`id_category_part`, `Area`,`Category`, `Types`) VALUES
  (1,'General','Instrument Type', 'Guitar'),
  (2,'General','Orientation','Guitar'),
  (3,'Body','Shape','Guitar'),
  (4,'Body','Neck Set','Guitar'),
  (5,'Body','Material','Guitar'),
  (6,'Body','Scale Length','Guitar'),
  (7,'Body','Countours','Guitar'),
  (8,'Body','Pickup Route','Guitar'),
  (9,'Body','Front Color','Guitar'),
  (10,'Body','Back Color','Guitar'),
  (11,'Body','Finish Type','Guitar'),
  (12,'Body','Front Binding','Guitar'),
  (13,'Body','Back Binding','Guitar'),
  (14,'Parts','Hardware Color','Guitar'),
  (15,'Parts','Pickguard','Guitar'),
  (16,'Parts','Bridge','Guitar'),
  (17,'Parts','Pickups','Guitar'),
  (18,'Parts','Mounting Rings','Guitar'),
  (19,'Parts','Control Knobs','Guitar'),
  (20,'Parts','Switch','Guitar'),
  (21,'Parts','Jackplate','Guitar'),
  (22,'Parts','Backplate','Guitar'),
  (23,'Parts','Nut','Guitar'),
  (24,'Parts','Tuners','Guitar'),
  (25,'Neck','Neck Shape','Guitar'),
  (26,'Neck','Material','Guitar'),
  (27,'Neck','Neck Width','Guitar'),
  (28,'Neck','Fingerboard','Guitar'),
  (29,'Neck','Fingerboard Radius','Guitar'),
  (30,'Neck','Number Of Frets','Guitar'),
  (31,'Neck','Fret Size','Guitar'),
  (32,'Neck','Fingerboard end','Guitar'),
  (33,'Neck','Inlays','Guitar'),
  (34,'Neck','Inlays Material','Guitar'),
  (35,'Neck','Side Dots','Guitar'),
  (36,'Neck','Headstock Shape','Guitar'),
  (37,'Neck','Reverse Headstock','Guitar'),
  (38,'Neck','Tilt Back Headstock','Guitar'),
  (39,'Neck','Neck Finish','Guitar'),
  (40,'Neck','Headstock Finish','Guitar'),
  (41,'Neck','Fingerboard Binding','Guitar'),
  (42,'Neck','Headstock Binding','Guitar'),
  (43,'Neck','Logo Special Instruction','Guitar'),
  (44,'General','Instrument Type','Bass'),
  (45,'General','Orientation','Bass'),
  (46,'Body','Shape','Bass'),
  (47,'Body','Neck Set','Bass'),
  (48,'Body','Material','Bass'),
  (49,'Body','Scale Length','Bass'),
  (50,'Body','Countours','Bass'),
  (51,'Body','Pickup Route','Bass'),
  (52,'Body','Front Color','Bass'),
  (53,'Body','Back Color','Bass'),
  (54,'Body','Finish Type','Bass'),
  (55,'Body','Front Binding','Bass'),
  (56,'Body','Back Binding','Bass'),
  (57,'Parts','Hardware Color','Bass'),
  (58,'Parts','Pickguard','Bass'),
  (59,'Parts','Bridge','Bass'),
  (60,'Parts','Pickups','Bass'),
  (61,'Parts','Mounting Rings','Bass'),
  (62,'Parts','Control Knobs','Bass'),
  (63,'Parts','Switch','Bass'),
  (64,'Parts','Jackplate','Bass'),
  (65,'Parts','Backplate','Bass'),
  (66,'Parts','Nut','Bass'),
  (67,'Parts','Tuners','Bass'),
  (68,'Neck','Neck Shape','Bass'),
  (69,'Neck','Material','Bass'),
  (70,'Neck','Neck Width','Bass'),
  (71,'Neck','Fingerboard','Bass'),
  (72,'Neck','Fingerboard Radius','Bass'),
  (73,'Neck','Number Of Frets','Bass'),
  (74,'Neck','Fret Size','Bass'),
  (75,'Neck','Fingerboard end','Bass'),
  (76,'Neck','Inlays','Bass'),
  (77,'Neck','Inlays Material','Bass'),
  (78,'Neck','Side Dots','Bass'),
  (79,'Neck','Headstock Shape','Bass'),
  (80,'Neck','Reverse Headstock','Bass'),
  (81,'Neck','Tilt Back Headstock','Bass'),
  (82,'Neck','Neck Finish','Bass'),
  (83,'Neck','Headstock Finish','Bass'),
  (84,'Neck','Fingerboard Binding','Bass'),
  (85,'Neck','Headstock Binding','Bass'),
  (86,'Neck','Logo Special Instruction','Bass')
;

INSERT INTO `products` (`id_product`, `nama_product`, `type`, `stok`, `price`, `color`, `isContent`, `shortQuotes`, `isSpecial`, `weight`, `Category`, `detail_product`, `Features`, `mainPhoto`, `created_at`, `updated_at`) 
VALUES
  (NULL, 'Electric Guitar', 'Part', '30', '3000000', '-', NULL, NULL, NULL, '2000', '1', '', NULL, NULL, NULL, NULL), 
  (NULL, 'Acoustic Guitar', 'Part', '30', '2000000', '-', NULL, NULL, NULL, '2000', '1', '', NULL, NULL, NULL, NULL), 
  (NULL, 'Bass Guitar', 'Part', '30', '4000000', '-', NULL, NULL, NULL, '2000', '1', '', NULL, NULL, NULL, NULL), 
  (NULL, 'Semi-Hollow Guitar', 'Part', '30', '5000000', '-', NULL, NULL, NULL, '2000', '1', '', NULL, NULL, NULL, NULL), 
  (NULL, 'Lap Steel Guitar', 'Part', '30', '6000000', '-', NULL, NULL, NULL, '2000', '1', '', NULL, NULL, NULL, NULL)
;






