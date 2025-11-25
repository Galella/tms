## 🚀 **Panduan Implementasi Teknis TMS**

### **🛠️ Stack Teknologi & Konfigurasi**
1. **Backend**: Laravel 12 dengan struktur modular
2. **Frontend**: AdminLTE 3 + Blade Components
3. **Database**: MySQL dengan constraint foreign key
4. **Security**: RBAC dengan Spatie Laravel Permission

### **🗄️ Struktur Database Kritis**

#### **Tabel Inti & Relasi**
```sql
-- Tabel utama dengan multi-site capability
CREATE TABLE `terminals` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(10) UNIQUE NOT NULL
);

-- Master fisik kontainer dengan validasi ISO 6346
CREATE TABLE `containers` (
  `container_number` VARCHAR(11) PRIMARY KEY,
  `size` ENUM('20','40','45') NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `ownership` ENUM('COC','SOC','FU') NOT NULL,
  `iso_code` VARCHAR(4) NOT NULL
);

-- Inventory real-time dengan constraint unik
CREATE TABLE `active_inventory` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `terminal_id` BIGINT NOT NULL,
  `container_number` VARCHAR(11) NOT NULL,
  `customer_id` BIGINT NULL,
  `shipping_line_id` BIGINT NULL,
  `status` ENUM('FULL','EMPTY') NOT NULL,
  `block` VARCHAR(10) NOT NULL,
  `row` VARCHAR(10) NOT NULL,
  `tier` VARCHAR(10) NOT NULL,
  `date_in` DATETIME NOT NULL,
  UNIQUE KEY `unique_active_container` (`terminal_id`, `container_number`),
  FOREIGN KEY (`terminal_id`) REFERENCES `terminals`(`id`),
  FOREIGN KEY (`container_number`) REFERENCES `containers`(`container_number`)
);
```

### **⚡ Modul Prioritas Implementasi**

#### **1. Sistem Autentikasi & RBAC**
```php
// app/Models/User.php
public function terminals()
{
    return $this->belongsToMany(Terminal::class);
}

public function hasTerminalPermission($permission, $terminal)
{
    return $this->hasPermissionTo("{$terminal->code}.{$permission}");
}
```

#### **2. Validasi Container ISO 6346**
```php
// app/Services/ContainerValidationService.php
public function validateContainerNumber($containerNumber)
{
    // Validasi format: 4 huruf pemilik + 6 digit serial + 1 check digit
    $isValid = $this->checkISO6346($containerNumber);
    $exists = Container::where('container_number', $containerNumber)->exists();
    
    return $isValid && !$exists;
}
```

#### **3. Business Logic Inbound/Outbound**
```php
// app/Services/GateOperationService.php
public function processTruckIn($data)
{
    DB::transaction(function () use ($data) {
        // 1. Validasi duplikasi active inventory
        if ($this->isDuplicateActive($data['terminal_id'], $data['container_number'])) {
            throw new \Exception('Container sudah aktif di terminal');
        }
        
        // 2. Catat inbound movement
        TruckMovement::create($data);
        
        // 3. Tambah ke active inventory
        ActiveInventory::create([
            'terminal_id' => $data['terminal_id'],
            'container_number' => $data['container_number'],
            'customer_id' => $data['container_type'] === 'FULL' ? $data['customer_id'] : null,
            'shipping_line_id' => $data['container_type'] === 'EMPTY' ? $data['shipping_line_id'] : null,
            'status' => $data['container_type'],
            'block' => $data['block'],
            'row' => $data['row'],
            'tier' => $data['tier'],
            'date_in' => now()
        ]);
    });
}
```

### **📊 Dashboard & Reporting Engine**

#### **KPI Real-time**
```php
// app/Services/DashboardService.php
public function getTerminalKPIs($terminalId)
{
    return [
        'total_stock' => ActiveInventory::where('terminal_id', $terminalId)->count(),
        'throughput_today' => TruckMovement::whereDate('created_at', today())
            ->where('terminal_id', $terminalId)
            ->count(),
        'avg_dwell_time' => ActiveInventory::where('terminal_id', $terminalId)
            ->selectRaw('AVG(DATEDIFF(NOW(), date_in)) as avg_days')
            ->first()->avg_days
    ];
}
```

### **🔒 Security Implementation**

#### **Middleware Multi-Site**
```php
// app/Http/Middleware/TerminalAccess.php
public function handle($request, $next)
{
    $terminal = $request->route('terminal');
    
    if (!auth()->user()->terminals->contains($terminal)) {
        abort(403, 'Akses terminal tidak diizinkan');
    }
    
    return $next($request);
}
```

### **📋 Checklist Go-Live**

#### **Fase 1 (Week 1-2)**
- [ ] Setup Laravel + AdminLTE
- [ ] Implementasi RBAC & multi-site
- [ ] Master data (terminals, containers, customers)
- [ ] Modul Truck IN/OUT dengan validasi

#### **Fase 2 (Week 3-4)**
- [ ] Modul Rail IN/OUT
- [ ] Inventory management
- [ ] Basic dashboard
- [ ] Integration testing

#### **Fase 3 (Week 5-6)**
- [ ] Advanced reporting
- [ ] Dwell time analytics
- [ ] Performance optimization
- [ ] User training & UAT

---

## 🎯 **Poin Kritis Success Factor**

1. **Data Integrity**: Validasi ISO 6346 dan constraint database
2. **Multi-Site Isolation**: RBAC dengan binding terminal_id
3. **Real-time Inventory**: Single source of truth di `active_inventory`
4. **Scalability**: Desain modular untuk tambahan fitur future

**Tim technical sudah dapat mulai eksekusi development berdasarkan blueprint final ini.** 🎉