<?php

namespace App\Enums;

/**
 * 執行条件.
 */
enum FrontOrderType: int
{
  case Market = 10;                   // 成行
  case MorningMarket = 13;            // 寄成（前場）
  case AfternoonMarket = 14;          // 寄成（後場）
  case MorningClosingMarket = 15;     // 引成（前場）
  case AfternoonClosingMarket = 16;   // 引成（後場）
  case IOCMarket = 17;                // IOC成行
  case Limit = 20;                    // 指値
  case MorningLimit = 21;             // 寄指（前場）
  case AfternoonLimit = 22;           // 寄指（後場）
  case MorningClosingLimit = 23;      // 引指（前場）
  case AfternoonClosingLimit = 24;    // 引指（後場）
  case MorningNonLimit = 25;          // 不成（前場）
  case AfternoonNonLimit = 26;        // 不成（後場）
  case IOCLimit = 27;                 // IOC指値
  case StopLimit = 30;                // 逆指値

  /**
   * Get the label for the enum value.
   */
  public function label(): string
  {
    return match ($this) {
      self::Market => '成行',                          // Market
      self::MorningMarket => '寄成（前場）',            // Morning Market
      self::AfternoonMarket => '寄成（後場）',          // Afternoon Market
      self::MorningClosingMarket => '引成（前場）',     // Morning Closing Market
      self::AfternoonClosingMarket => '引成（後場）',   // Afternoon Closing Market
      self::IOCMarket => 'IOC成行',                    // IOC Market
      self::Limit => '指値',                           // Limit
      self::MorningLimit => '寄指（前場）',             // Morning Limit
      self::AfternoonLimit => '寄指（後場）',           // Afternoon Limit
      self::MorningClosingLimit => '引指（前場）',      // Morning Closing Limit
      self::AfternoonClosingLimit => '引指（後場）',    // Afternoon Closing Limit
      self::MorningNonLimit => '不成（前場）',          // Morning Non-Limit
      self::AfternoonNonLimit => '不成（後場）',        // Afternoon Non-Limit
      self::IOCLimit => 'IOC指値',                     // IOC Limit
      self::StopLimit => '逆指値',                     // Stop Limit
    };
  }
}
